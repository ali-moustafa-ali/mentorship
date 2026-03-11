<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        // Create tags first
        $tagsMap = [
            'widgets' => Tag::create(['name' => 'widgets', 'slug' => 'widgets', 'color' => '#6c63ff']),
            'ui' => Tag::create(['name' => 'ui', 'slug' => 'ui', 'color' => '#ec4899']),
            'state' => Tag::create(['name' => 'state', 'slug' => 'state', 'color' => '#f59e0b']),
            'navigation' => Tag::create(['name' => 'navigation', 'slug' => 'navigation', 'color' => '#3b82f6']),
            'lifecycle' => Tag::create(['name' => 'lifecycle', 'slug' => 'lifecycle', 'color' => '#8b5cf6']),
            'layout' => Tag::create(['name' => 'layout', 'slug' => 'layout', 'color' => '#14b8a6']),
            'networking' => Tag::create(['name' => 'networking', 'slug' => 'networking', 'color' => '#ef4444']),
            'provider' => Tag::create(['name' => 'provider', 'slug' => 'provider', 'color' => '#4ade80']),
            'dart' => Tag::create(['name' => 'dart', 'slug' => 'dart', 'color' => '#0ea5e9']),
        ];

        $topics = [
            [
                'title' => 'Widgets',
                'category' => 'أساسيات',
                'tags' => ['widgets', 'ui', 'dart'],
                'body' => 'كل حاجة في Flutter هي Widget. الـ Widgets هي اللبنات الأساسية لبناء واجهة المستخدم.

في نوعين أساسيين:
- StatelessWidget — مبيتغيرش بعد ما بيترسم
- [[StatefulWidget]] — بيتغير لما الـ State بتتغير

```dart
class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        appBar: AppBar(title: Text("Flutter Wiki")),
        body: Center(child: Text("Hello World")),
      ),
    );
  }
}
```

من أهم الـ Widgets:
- `Container` — زي الـ div في HTML
- `Row` و `Column` — للتخطيط الأفقي والعمودي
- `ListView` — لعرض قوائم
- `Stack` — لتراكب العناصر فوق بعض

شوف كمان [[State Management]] و [[Navigation]].',
            ],
            [
                'title' => 'StatefulWidget',
                'category' => 'أساسيات',
                'tags' => ['widgets', 'state', 'lifecycle'],
                'body' => 'الـ StatefulWidget بيستخدم لما يكون عندك بيانات بتتغير وعايز الواجهة تتحدث.

بيتكون من كلاسين:
1. الـ Widget نفسه
2. الـ State class اللي بيحتوي على البيانات والـ `build` method

```dart
class CounterWidget extends StatefulWidget {
  @override
  _CounterWidgetState createState() => _CounterWidgetState();
}

class _CounterWidgetState extends State<CounterWidget> {
  int _counter = 0;

  void _increment() {
    setState(() {
      _counter++;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text("العداد: $_counter"),
        ElevatedButton(
          onPressed: _increment,
          child: Text("زود"),
        ),
      ],
    );
  }
}
```

`setState()` هي اللي بتخلي Flutter يعيد رسم الـ Widget.

لما التطبيق يكبر، الأفضل تستخدم [[State Management]] بدل `setState` مباشرة.

شوف كمان [[Widgets]] و [[Widget Lifecycle]].',
            ],
            [
                'title' => 'State Management',
                'category' => 'متقدم',
                'tags' => ['state', 'provider', 'dart'],
                'body' => 'إدارة الحالة (State Management) من أهم المواضيع في Flutter.

## الطرق المختلفة:

### 1. setState
أبسط طريقة، مناسبة للويدجتس البسيطة:
```dart
setState(() {
  _value = newValue;
});
```

### 2. Provider
الأكثر استخداماً وموصى بيه من Google:
```dart
class CounterProvider extends ChangeNotifier {
  int _count = 0;
  int get count => _count;

  void increment() {
    _count++;
    notifyListeners();
  }
}

Consumer<CounterProvider>(
  builder: (context, counter, child) {
    return Text("${counter.count}");
  },
)
```

### 3. Riverpod
نسخة محسنة من Provider:
```dart
final counterProvider = StateNotifierProvider<CounterNotifier, int>(
  (ref) => CounterNotifier(),
);
```

### 4. BLoC
بيستخدم Streams وEvents:
```dart
class CounterBloc extends Bloc<CounterEvent, int> {
  CounterBloc() : super(0);
}
```

شوف كمان [[StatefulWidget]] و [[Navigation]].',
            ],
            [
                'title' => 'Navigation',
                'category' => 'أساسيات',
                'tags' => ['navigation', 'ui'],
                'body' => 'الـ Navigation في Flutter بيستخدم نظام الـ Routes.

## الطريقة الأساسية:
```dart
Navigator.push(
  context,
  MaterialPageRoute(builder: (context) => SecondPage()),
);

Navigator.pop(context);
```

## Named Routes:
```dart
MaterialApp(
  routes: {
    "/": (context) => HomePage(),
    "/details": (context) => DetailsPage(),
    "/settings": (context) => SettingsPage(),
  },
);

Navigator.pushNamed(context, "/details");
```

## تمرير بيانات:
```dart
Navigator.push(
  context,
  MaterialPageRoute(
    builder: (context) => DetailsPage(id: itemId),
  ),
);
```

## GoRouter (متقدم):
```dart
final router = GoRouter(
  routes: [
    GoRoute(
      path: "/",
      builder: (context, state) => HomePage(),
    ),
  ],
);
```

شوف كمان [[Widgets]] و [[State Management]].',
            ],
            [
                'title' => 'Widget Lifecycle',
                'category' => 'أساسيات',
                'tags' => ['widgets', 'lifecycle'],
                'body' => 'كل [[StatefulWidget]] بيمر بمراحل حياة محددة:

## المراحل:

### 1. `createState()`
أول method بيتنادى لما الويدجت بيتعمل:
```dart
@override
_MyWidgetState createState() => _MyWidgetState();
```

### 2. `initState()`
بيتنادى مرة واحدة بس لما الـ State بيتعمل:
```dart
@override
void initState() {
  super.initState();
  _controller = AnimationController(vsync: this);
  _loadData();
}
```

### 3. `didChangeDependencies()`
بيتنادى بعد `initState` ولما الـ dependencies تتغير.

### 4. `build()`
بيتنادى كل مرة الـ UI محتاج يترسم:
```dart
@override
Widget build(BuildContext context) {
  return Container();
}
```

### 5. `didUpdateWidget()`
بيتنادى لما الـ parent widget يتغير.

### 6. `dispose()`
بيتنادى لما الويدجت بيتشال:
```dart
@override
void dispose() {
  _controller.dispose();
  super.dispose();
}
```

شوف كمان [[Widgets]] و [[State Management]].',
            ],
            [
                'title' => 'Layouts',
                'category' => 'أساسيات',
                'tags' => ['layout', 'ui', 'widgets'],
                'body' => 'الـ Layout في Flutter بيعتمد على [[Widgets]] خاصة بالتخطيط:

## Row & Column
`Row` للترتيب الأفقي و `Column` للعمودي:
```dart
Column(
  mainAxisAlignment: MainAxisAlignment.center,
  crossAxisAlignment: CrossAxisAlignment.start,
  children: [
    Text("العنصر الأول"),
    Text("العنصر الثاني"),
    Text("العنصر الثالث"),
  ],
)
```

## Expanded & Flexible
لتوزيع المساحة:
```dart
Row(
  children: [
    Expanded(flex: 2, child: Container(color: Colors.red)),
    Expanded(flex: 1, child: Container(color: Colors.blue)),
  ],
)
```

## Stack
لتراكب العناصر:
```dart
Stack(
  children: [
    Image.network("https://example.com/bg.jpg"),
    Positioned(
      bottom: 16,
      right: 16,
      child: Text("نص فوق الصورة"),
    ),
  ],
)
```

## Padding & SizedBox
```dart
Padding(
  padding: EdgeInsets.all(16),
  child: Text("محتوى"),
)

SizedBox(height: 20)
```

شوف كمان [[Widgets]] و [[Navigation]].',
            ],
            [
                'title' => 'HTTP و API',
                'category' => 'متقدم',
                'tags' => ['networking', 'dart'],
                'body' => 'التعامل مع الـ APIs في Flutter باستخدام حزمة `http` أو `dio`.

## حزمة http
```dart
import "package:http/http.dart" as http;
import "dart:convert";

Future<List<User>> fetchUsers() async {
  final response = await http.get(
    Uri.parse("https://api.example.com/users"),
  );

  if (response.statusCode == 200) {
    final List data = json.decode(response.body);
    return data.map((json) => User.fromJson(json)).toList();
  } else {
    throw Exception("فشل في تحميل البيانات");
  }
}
```

## حزمة Dio (أقوى)
```dart
final dio = Dio();

Future<Response> getUsers() async {
  try {
    final response = await dio.get("/users");
    return response;
  } on DioException catch (e) {
    print("خطأ: ${e.message}");
    rethrow;
  }
}
```

## Model class
```dart
class User {
  final int id;
  final String name;
  final String email;

  User({required this.id, required this.name, required this.email});

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json["id"],
      name: json["name"],
      email: json["email"],
    );
  }
}
```

## عرض البيانات
```dart
FutureBuilder<List<User>>(
  future: fetchUsers(),
  builder: (context, snapshot) {
    if (snapshot.connectionState == ConnectionState.waiting) {
      return CircularProgressIndicator();
    }
    if (snapshot.hasError) {
      return Text("خطأ: ${snapshot.error}");
    }
    return ListView.builder(
      itemCount: snapshot.data!.length,
      itemBuilder: (context, index) {
        return ListTile(title: Text(snapshot.data![index].name));
      },
    );
  },
)
```

شوف كمان [[State Management]] و [[Navigation]].',
            ],
        ];

        foreach ($topics as $topicData) {
            $tagNames = $topicData['tags'] ?? [];
            unset($topicData['tags']);

            $topic = Topic::create($topicData);

            // Attach tags
            $tagIds = collect($tagNames)->map(fn($name) => $tagsMap[$name]->id)->all();
            $topic->tags()->attach($tagIds);

            // Save initial version
            $topic->saveVersion('إنشاء الموضوع');
        }

        // Pin the Widgets topic
        Topic::where('slug', 'widgets')->update(['is_pinned' => true]);
    }
}
