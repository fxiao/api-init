# API 基础代码

基于 Lumen 6.x 实现的 RESTful API。

## 组件

- [`dingo/api`](https://github.com/dingo/api) [[中文](https://learnku.com/docs/dingo-api/2.0.0)]
- [JWT](https://github.com/tymondesigns/jwt-auth)

## 开发

```bash
# 复制
cp .env.example .env

# 编辑数据库和域名相关配置
vi .env

sh init
```

### 添加 users 数据

```bash
php artisan db:seed --class=UsersTableSeeder
```

密码默认为：`123456`

### SQL log 显示

`.env` 设置 `APP_DEBUG=true` 时，生成全局 `SQL log` 日志文件 `storage/logs/sql-年-月-日.log`

`SQL log` 精确显示:

```php
\DB::enableQueryLog();

... 业务 ...

dd(\DB::getQueryLog());

```

### 脚手架

URL：`/dev-helpers`

`.env` 同时 设置 `APP_DEBUG=true` 和 `DEV_HELPERS=true` 才有效

```php
# dev
DEV_HELPERS=true
# App\Models\
DEV_HELPERS_MODELS_PATH=
# App\Http\Controllers\
DEV_HELPERS_CONTROLLER_PATH=App\Controllers\
# App\Transformers\
DEV_HELPERS_TRANSFORMER_PATH=
# routes\
DEV_HELPERS_ROUTE_PATH=
```
