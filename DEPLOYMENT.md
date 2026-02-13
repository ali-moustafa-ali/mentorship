# Deployment Notes (alimoustafa.in)

This repository is deployed on a VPS and reverse-proxied behind host Nginx.

## Production URL

- App: `https://alimoustafa.in/mentor/`

## Nginx (Host) Routing

On the server, the vhost file is:

- `/etc/nginx/sites-available/alimoustafa.in`

### Root Redirect

To avoid the default Nginx landing page on `/`, we redirect `/` to the app subpath.
This also keeps query strings (example: `/?domain=cpp`).

```nginx
# Root redirect (keep query string, e.g. /?domain=cpp)
location = / {
    return 302 /mentor/$is_args$args;
}
```

### Mentor Subpath Proxy

The app is served by a Docker Nginx container bound to `127.0.0.1:8083`.

```nginx
location = /mentor {
    return 301 /mentor/;
}

location /mentor/ {
    proxy_pass http://127.0.0.1:8083/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

### Apply Changes

After editing the vhost file on the server:

```bash
nginx -t
systemctl reload nginx
```

## Docker Compose (Server)

The app stack is in `/var/www/mentor` and uses:

- `docker-compose.prod.yml`
- containers: `mentor_nginx`, `mentor_app`, `mentor_db`

