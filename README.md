## 校园易 · 校园二手交易与互助平台

本项目以 “校园二手交易与互助平台” 课程题目为核心，基于 Laravel 11 + MySQL 搭建，提供闲置物品发布、互助任务发布与展示的最小可运行版本，方便通过 `php artisan serve` 直接体验。

### 环境要求

- PHP >= 8.2
- Composer
- MySQL >= 5.7 或 MariaDB >= 10.3
- Node.js >= 18 和 npm（用于前端资源编译）

### 快速开始

#### 1. 克隆项目并安装依赖

```bash
git clone <项目地址>
cd finalprojects
composer install
npm install
```

> **给朋友的提示**：仓库依赖 Laravel 11 + MySQL。只要机器上已经安装了 PHP 8.2、Composer、Node.js、MySQL，就可以完全复刻以下步骤。所有命令默认在项目根目录执行。

#### 2. 配置环境变量

**Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
```

**Linux/Mac:**
```bash
cp .env.example .env
```

#### 3. 创建数据库

在 MySQL 中创建数据库：

```sql
CREATE DATABASE campus_market CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

或者使用命令行：

```bash
mysql -u root -p -e "CREATE DATABASE campus_market CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

#### 4. 配置数据库连接

编辑 `.env` 文件，设置数据库信息：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_market
DB_USERNAME=root
DB_PASSWORD=你的MySQL密码

SESSION_DRIVER=database
```

**注意：** 如果使用 SQLite（仅用于开发测试），可以设置：
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```
然后需要创建 SQLite 文件：
```bash
touch database/database.sqlite  # Linux/Mac
# 或手动创建 database/database.sqlite 文件 (Windows)
```

#### 5. 生成应用密钥

```bash
php artisan key:generate
```

#### 6. 运行数据库迁移和填充数据

```bash
php artisan migrate
php artisan db:seed
```

这将创建所有必要的数据库表并填充示例数据。

#### 7. 编译前端资源

```bash
npm run build
```

开发时可以使用：
```bash
npm run dev
```

#### 8. 启动开发服务器

```bash
php artisan serve
```

浏览器访问 **http://127.0.0.1:8000** 即可看到概览页，顶部导航可进入 "二手交易" 与 "互助任务" 页面，支持筛选、简单发布表单（未开启正式登录，仅用于演示流程）。

### 命令顺序速查（以 Windows PowerShell + MySQL 为例）

```powershell
# 1. 克隆仓库（朋友替换为自己的 GitHub 地址）
git clone https://github.com/你的用户名/校园易.git
cd 校园易 或 finalprojects

# 2. 安装 PHP / JS 依赖
composer install
npm install

# 3. 复制环境变量文件并修改数据库信息
Copy-Item .env.example .env
# 用任意编辑器把 DB_DATABASE / DB_USERNAME / DB_PASSWORD 改成自己 MySQL 的值

# 4. 创建数据库（也可以用图形化工具，如 Navicat）
mysql -u root -p -e "CREATE DATABASE campus_market CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. 生成 APP_KEY
php artisan key:generate

# 6. 执行迁移与填充示例数据
php artisan migrate --seed

# 7. 编译前端静态资源
npm run build

# 8. 启动开发服务器
php artisan serve
# 浏览器访问 http://127.0.0.1:8000
```

#### 9. 如果遇到问题，按顺序排查

1. **确认依赖是否安装**：`composer install`、`npm install` 是否成功；  
2. **确认 `.env`**：数据库名称、账号、密码必须与 MySQL 中实际创建的一致；  
3. **确认数据库是否初始化**：`php artisan migrate --seed` 可一键重建表并写入示例数据；  
4. **确认应用密钥**：若报 `APP_KEY missing`，重新执行 `php artisan key:generate`；  
5. **确认前端资源**：若页面没有样式，重新运行 `npm run build`（或开发模式 `npm run dev`）；  
6. **启动顺序**：建议先启动数据库，再执行 PHP 相关命令，最后 `php artisan serve` 提供访问入口。

按照以上顺序执行，就能在任何 MySQL 环境（朋友的电脑）里完整跑起来。

### 常见问题

**Q: 迁移时提示数据库连接失败？**  
A: 检查 `.env` 文件中的数据库配置是否正确，确保 MySQL 服务已启动，数据库已创建。

**Q: 提示 `APP_KEY` 未设置？**  
A: 运行 `php artisan key:generate` 生成应用密钥。

**Q: 前端样式未加载？**  
A: 确保已运行 `npm install` 和 `npm run build`，或使用 `npm run dev` 启动开发模式。

**Q: 会话不工作？**  
A: 确保 `SESSION_DRIVER=database` 且已运行迁移（会创建 `sessions` 表）。

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
