# 🎓 LearnQuest

ゲーミフィケーションを取り入れた学習プラットフォームです。

## 📚 機能

- **コース管理**: 動画・テキスト・クイズ・課題など多様なレッスンタイプ
- **ゲーミフィケーション**: ポイント・レベル・バッジシステム
- **学習進捗管理**: 連続学習日数、完了率などの追跡
- **管理画面**: コース・レッスン・バッジ・ユーザーの管理
- **動画埋め込み**: YouTube / Google Drive対応

## 🛠 技術スタック

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3 + Inertia.js
- **CSS**: Tailwind CSS
- **Database**: MySQL (Sail) / SQLite (ローカル)
- **Development**: Laravel Sail (Docker)

## 🚀 セットアップ

### 前提条件

- Docker Desktop がインストールされていること

### Laravel Sail を使用したセットアップ

```bash
# リポジトリをクローン
git clone https://github.com/YOUR_USERNAME/LearnQuest.git
cd LearnQuest

# .env ファイルを作成
cp .env.example .env

# Composer依存関係をインストール（Sailなしで初回のみ）
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# Sailでコンテナを起動
./vendor/bin/sail up -d

# アプリケーションキーを生成
./vendor/bin/sail artisan key:generate

# データベースマイグレーション
./vendor/bin/sail artisan migrate

# シーダーを実行（初期データ）
./vendor/bin/sail artisan db:seed

# NPM依存関係をインストールしてビルド
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### アクセス

- **アプリケーション**: http://localhost
- **Vite（開発時）**: http://localhost:5173

## 📖 Sailコマンド

```bash
# コンテナを起動
./vendor/bin/sail up -d

# コンテナを停止
./vendor/bin/sail down

# Artisanコマンド
./vendor/bin/sail artisan [command]

# NPMコマンド
./vendor/bin/sail npm [command]

# Composerコマンド
./vendor/bin/sail composer [command]

# MySQLに接続
./vendor/bin/sail mysql

# 開発サーバー（Vite）
./vendor/bin/sail npm run dev
```

## 👤 初期アカウント

シーダー実行後、以下のアカウントが作成されます：

| 役割 | メールアドレス | パスワード |
|------|---------------|-----------|
| 管理者 | admin@example.com | password |
| ユーザー | user@example.com | password |

## 📂 プロジェクト構成

```
LearnQuest/
├── app/
│   ├── Http/Controllers/     # コントローラー
│   │   └── Admin/            # 管理画面用
│   └── Models/               # Eloquentモデル
├── database/
│   ├── migrations/           # マイグレーション
│   └── seeders/              # シーダー
├── docker/                   # Docker設定
├── resources/
│   └── js/
│       ├── Components/       # Vueコンポーネント
│       ├── Layouts/          # レイアウト
│       └── Pages/            # ページコンポーネント
│           └── Admin/        # 管理画面
├── routes/
│   ├── web.php               # Webルート
│   └── auth.php              # 認証ルート
└── docker-compose.yml        # Docker Compose設定
```

## 📝 ライセンス

MIT License
