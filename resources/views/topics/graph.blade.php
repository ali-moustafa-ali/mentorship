@extends('layouts.app')
@section('title', 'خريطة المعرفة')
@section('content')

<style>
    /* Override main-content padding for full-screen graph */
    .main-content { padding: 0 !important; overflow: hidden; }
</style>

<div id="graph-container" style="
    width: 100%;
    height: 100vh;
    background: var(--bg-card);
    overflow: hidden;
    position: relative;
">
    <div style="position: absolute; top: 16px; right: 16px; z-index: 10; display: flex; gap: 8px; align-items: center;">
        <span style="font-size: 0.8rem; color: var(--text-muted);">⬤ المواضيع —— الروابط · اسحب العقد لتحريكها · عجلة الماوس للتكبير</span>
        <a href="{{ route('topics.index') }}" class="btn btn-secondary btn-sm" style="white-space: nowrap;">→ رجوع</a>
    </div>
    <div style="position: absolute; bottom: 24px; left: 24px; z-index: 10; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 12px 18px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 0.78rem; color: var(--text-muted); width: 50px; white-space: nowrap;">📏 المسافة</span>
            <input type="range" id="distance-slider" min="50" max="600" value="200" style="width: 140px; accent-color: var(--accent); cursor: pointer;">
            <span id="distance-value" style="font-size: 0.78rem; color: var(--text-secondary); min-width: 30px; text-align: center;">200</span>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 0.78rem; color: var(--text-muted); width: 50px; white-space: nowrap;">🧲 التنافر</span>
            <input type="range" id="charge-slider" min="100" max="5000" value="1500" style="width: 140px; accent-color: var(--accent); cursor: pointer;" dir="ltr">
            <span id="charge-value" style="font-size: 0.78rem; color: var(--text-secondary); min-width: 30px; text-align: center;">1500</span>
        </div>
    </div>
    <div id="graph-tooltip" style="
        position: absolute;
        background: var(--bg-secondary);
        border: 1px solid var(--accent);
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 0.85rem;
        color: var(--text-primary);
        pointer-events: none;
        display: none;
        z-index: 50;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    "></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.9.0/d3.min.js"></script>
<script>
const nodes = @json($nodes);
const links = @json($links);

const NODE_RADIUS = 18;
const categoryColors = {
    'أساسيات': { fill: '#7c6cff', glow: 'rgba(108,99,255,0.5)' },
    'متقدم':   { fill: '#f59e0b', glow: 'rgba(245,158,11,0.5)' },
    'غير مصنف': { fill: '#64748b', glow: 'rgba(100,116,139,0.4)' }
};

const container = document.getElementById('graph-container');
const tooltip = document.getElementById('graph-tooltip');
const width = container.clientWidth;
const height = container.clientHeight;

const svg = d3.select('#graph-container')
    .append('svg')
    .attr('width', width)
    .attr('height', height);

// Defs for glow filters and gradients
const defs = svg.append('defs');
Object.entries(categoryColors).forEach(([cat, c]) => {
    const id = 'glow-' + cat.replace(/\s/g, '');
    const filter = defs.append('filter').attr('id', id).attr('x', '-50%').attr('y', '-50%').attr('width', '200%').attr('height', '200%');
    filter.append('feGaussianBlur').attr('stdDeviation', '4').attr('result', 'blur');
    filter.append('feFlood').attr('flood-color', c.glow).attr('result', 'color');
    filter.append('feComposite').attr('in', 'color').attr('in2', 'blur').attr('operator', 'in').attr('result', 'shadow');
    const merge = filter.append('feMerge');
    merge.append('feMergeNode').attr('in', 'shadow');
    merge.append('feMergeNode').attr('in', 'SourceGraphic');
});

const g = svg.append('g');

// Zoom
const zoom = d3.zoom()
    .scaleExtent([0.3, 5])
    .on('zoom', (event) => g.attr('transform', event.transform));
svg.call(zoom);

// Center view initially
svg.call(zoom.transform, d3.zoomIdentity.translate(0, 0).scale(1));

const simulation = d3.forceSimulation(nodes)
    .force('link', d3.forceLink(links).id(d => d.id).distance(200))
    .force('charge', d3.forceManyBody().strength(-1500).distanceMax(1200))
    .force('center', d3.forceCenter(width / 2, height / 2))
    .force('collision', d3.forceCollide(100).iterations(4))
    .force('x', d3.forceX(width / 2).strength(0.005))
    .force('y', d3.forceY(height / 2).strength(0.005));

// Links — straight lines
const link = g.append('g')
    .selectAll('line')
    .data(links)
    .enter().append('line')
    .attr('stroke', 'rgba(140,160,200,0.25)')
    .attr('stroke-width', 1.5);

// Nodes group
const node = g.append('g')
    .selectAll('g')
    .data(nodes)
    .enter().append('g')
    .attr('cursor', 'pointer')
    .call(d3.drag()
        .on('start', dragstarted)
        .on('drag', dragged)
        .on('end', dragended));

// Outer glow circle (subtle)
node.append('circle')
    .attr('r', NODE_RADIUS + 4)
    .attr('fill', 'none')
    .attr('stroke', d => (categoryColors[d.category] || categoryColors['غير مصنف']).fill)
    .attr('stroke-width', 1)
    .attr('stroke-opacity', 0.3);

// Main circle
node.append('circle')
    .attr('r', NODE_RADIUS)
    .attr('fill', d => (categoryColors[d.category] || categoryColors['غير مصنف']).fill)
    .attr('stroke', 'rgba(255,255,255,0.2)')
    .attr('stroke-width', 1.5)
    .attr('filter', d => 'url(#glow-' + (d.category || 'غير مصنف').replace(/\s/g, '') + ')')
    .style('transition', 'r 0.2s ease');

// Label
node.append('text')
    .text(d => d.name)
    .attr('text-anchor', 'middle')
    .attr('dy', NODE_RADIUS + 16)
    .attr('font-size', '0.72rem')
    .attr('font-weight', '600')
    .attr('fill', 'var(--text-secondary)')
    .attr('font-family', 'Cairo')
    .attr('pointer-events', 'none');

// Hover effects
node.on('mouseover', function(event, d) {
    d3.select(this).select('circle:nth-child(2)').transition().duration(200).attr('r', NODE_RADIUS + 5);
    d3.select(this).select('circle:nth-child(1)').transition().duration(200).attr('r', NODE_RADIUS + 9);
    tooltip.style.display = 'block';
    tooltip.textContent = d.name + ' — ' + d.category;
    // Highlight connected links
    link.attr('stroke', l => (l.source.id === d.id || l.target.id === d.id) ? (categoryColors[d.category] || categoryColors['غير مصنف']).fill : 'rgba(140,160,200,0.12)')
        .attr('stroke-width', l => (l.source.id === d.id || l.target.id === d.id) ? 2.5 : 1);
})
.on('mousemove', (event) => {
    const rect = container.getBoundingClientRect();
    tooltip.style.left = (event.clientX - rect.left + 14) + 'px';
    tooltip.style.top = (event.clientY - rect.top - 36) + 'px';
})
.on('mouseout', function(event, d) {
    d3.select(this).select('circle:nth-child(2)').transition().duration(300).attr('r', NODE_RADIUS);
    d3.select(this).select('circle:nth-child(1)').transition().duration(300).attr('r', NODE_RADIUS + 4);
    tooltip.style.display = 'none';
    link.attr('stroke', 'rgba(140,160,200,0.25)').attr('stroke-width', 1.5);
})
.on('click', (event, d) => {
    // Keep working when the app is reverse-proxied under a subpath (e.g. /mentor).
    window.location.href = new URL('topics/' + d.slug, window.location.href).toString();
});

simulation.on('tick', () => {
    link.attr('x1', d => d.source.x)
        .attr('y1', d => d.source.y)
        .attr('x2', d => d.target.x)
        .attr('y2', d => d.target.y);
    node.attr('transform', d => 'translate(' + d.x + ',' + d.y + ')');
});

function dragstarted(event, d) {
    if (!event.active) simulation.alphaTarget(0.3).restart();
    d.fx = d.x; d.fy = d.y;
}
function dragged(event, d) { d.fx = event.x; d.fy = event.y; }
function dragended(event, d) {
    if (!event.active) simulation.alphaTarget(0);
    d.fx = null; d.fy = null;
}

// Slider controls
document.getElementById('distance-slider').addEventListener('input', function() {
    const val = +this.value;
    document.getElementById('distance-value').textContent = val;
    simulation.force('link').distance(val);
    simulation.alpha(0.5).restart();
});

document.getElementById('charge-slider').addEventListener('input', function() {
    const val = +this.value;
    document.getElementById('charge-value').textContent = val;
    simulation.force('charge').strength(-val);
    simulation.alpha(0.5).restart();
});
</script>

@endsection
