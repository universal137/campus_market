## 校园易 · 校园二手交易与互助平台

本项目以 “校园二手交易与互助平台” 课程题目为核心，基于 Laravel 11 + MySQL 搭建，提供闲置物品发布、互助任务发布与展示的最小可运行版本，方便通过 `php artisan serve` 直接体验。

### 快速开始

1. **复制 `.env.example` 并配置数据库**
   ```bash
   cp .env.example .env
   ```
   在 `.env` 中设置：
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=campus_market
   DB_USERNAME=root
   DB_PASSWORD=你的密码

   SESSION_DRIVER=database
   ```

2. **安装依赖并生成密钥**
   ```bash
   composer install
   php artisan key:generate
   ```

3. **迁移 + 填充演示数据**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **运行项目**
   ```bash
   php artisan serve
   ```
   浏览器访问 http://127.0.0.1:8000 即可看到概览页，顶部导航可进入 “二手交易” 与 “互助任务” 页面，支持筛选、简单发布表单（未开启正式登录，仅用于演示流程）。

### 功能概览

- **概览页**：展示热门分类、最新商品和互助任务，提供快捷导航；
- **二手交易**：
  - 分类/关键字筛选与分页；
  - 简易发布表单（输入联系人昵称 + 校园邮箱 + 商品信息）；
- **互助任务**：
  - 任务状态、关键字筛选；
  - 发布表单（昵称、邮箱、任务内容、奖励金额）；
- **数据库结构**：`users`、`categories`、`items`、`tasks`、`sessions` 等核心表已通过迁移定义，Seeder 自动生成示例数据；
- **会话存储**：默认使用数据库驱动，配合 `sessions` 表确保登录与状态管理可扩展。

### 下一步迭代建议

- 接入 Laravel Breeze / Fortify 以支持真正的注册、登录、邮箱验证；
- 为商品与任务增加多图上传、订单状态流转、站内消息等模块；
- 新增 Feature 测试覆盖发布与筛选流程，保障核心流程稳定。

如需更多信息，可查阅 `routes/web.php`、`app/Http/Controllers`、`resources/views` 等目录，了解当前的业务实现。*** End Patch
