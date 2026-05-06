# Code Citations

## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/mhzed/wstunnel/blob/736629a5a75aeaa81bc5b2391680425a8bff44b8/readme.md

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```


## License: MIT
https://github.com/trufflepig-forensics/borg-vinculum/blob/7064abe5d0fbec1f2f9e15077b9ac04ec8b70c0d/vagrant/vinculum/vinculum.nginx.jinja2

```
**Phase 4 完了 ✅**

次は **Phase 5: Nginx 設定**です。設定ファイルを作成します：

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

開いたら以下を貼り付けてください：

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    client_max_body_size 10M;

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme
```

