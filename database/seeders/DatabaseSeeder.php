<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'total_points' => 0,
            'level' => 1,
        ]);

        // Create test user
        User::create([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'total_points' => 150,
            'level' => 2,
            'streak_days' => 3,
        ]);

        // Create courses
        $course1 = Course::create([
            'title' => 'はじめてのプログラミング',
            'description' => 'プログラミングの基礎を学ぶ入門コースです。変数、条件分岐、ループなどの基本概念を理解し、簡単なプログラムを作成できるようになります。',
            'order' => 1,
            'points_reward' => 200,
            'is_published' => true,
        ]);

        $course2 = Course::create([
            'title' => 'Webデザイン基礎',
            'description' => 'HTMLとCSSを使ったWebページの作成方法を学びます。美しく使いやすいWebサイトを作るためのデザインの基本を習得できます。',
            'order' => 2,
            'points_reward' => 200,
            'is_published' => true,
        ]);

        $course3 = Course::create([
            'title' => 'データ分析入門',
            'description' => 'Excelやスプレッドシートを使ったデータ分析の基礎を学びます。グラフ作成やピボットテーブルなど、実務で使えるスキルを身につけます。',
            'order' => 3,
            'points_reward' => 250,
            'is_published' => true,
        ]);

        // Create lessons for course 1
        Lesson::create([
            'course_id' => $course1->id,
            'title' => 'プログラミングとは？',
            'description' => 'プログラミングの概要と、なぜ学ぶ価値があるのかを解説します。',
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration_minutes' => 10,
            'order' => 1,
            'points_reward' => 10,
            'is_published' => true,
        ]);

        Lesson::create([
            'course_id' => $course1->id,
            'title' => '変数を理解しよう',
            'description' => 'データを保存するための「変数」について学びます。',
            'type' => 'text',
            'content' => '<h3>変数とは？</h3><p>変数は、データを一時的に保存するための「箱」のようなものです。</p><h3>変数の使い方</h3><p>変数を使うと、後から値を参照したり、変更したりすることができます。</p><h3>例</h3><pre>name = "太郎"\nage = 25</pre>',
            'duration_minutes' => 15,
            'order' => 2,
            'points_reward' => 15,
            'is_published' => true,
        ]);

        Lesson::create([
            'course_id' => $course1->id,
            'title' => '条件分岐（if文）',
            'description' => '条件によって処理を分ける方法を学びます。',
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration_minutes' => 20,
            'order' => 3,
            'points_reward' => 20,
            'is_published' => true,
        ]);

        Lesson::create([
            'course_id' => $course1->id,
            'title' => '理解度チェック',
            'description' => 'ここまでの内容を確認するクイズです。',
            'type' => 'quiz',
            'content' => '<h3>クイズ</h3><p>以下の質問に答えてください：</p><ol><li>変数とは何ですか？</li><li>if文はどのような時に使いますか？</li><li>プログラミングを学ぶメリットは？</li></ol>',
            'duration_minutes' => 10,
            'order' => 4,
            'points_reward' => 25,
            'is_published' => true,
        ]);

        // Create lessons for course 2
        Lesson::create([
            'course_id' => $course2->id,
            'title' => 'HTMLの基本',
            'description' => 'Webページの骨組みを作るHTMLについて学びます。',
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration_minutes' => 15,
            'order' => 1,
            'points_reward' => 15,
            'is_published' => true,
        ]);

        Lesson::create([
            'course_id' => $course2->id,
            'title' => 'CSSでスタイリング',
            'description' => 'CSSを使ってWebページを美しく装飾する方法を学びます。',
            'type' => 'text',
            'content' => '<h3>CSSとは？</h3><p>CSS（Cascading Style Sheets）は、HTMLで作った骨組みに色やレイアウトを追加するための言語です。</p><h3>基本的な書き方</h3><pre>h1 {\n  color: blue;\n  font-size: 24px;\n}</pre>',
            'duration_minutes' => 20,
            'order' => 2,
            'points_reward' => 20,
            'is_published' => true,
        ]);

        Lesson::create([
            'course_id' => $course2->id,
            'title' => '実践課題：自己紹介ページを作ろう',
            'description' => '学んだことを活かして、自分の紹介ページを作成してみましょう。',
            'type' => 'assignment',
            'content' => '<h3>課題内容</h3><p>HTMLとCSSを使って、以下の要素を含む自己紹介ページを作成してください：</p><ul><li>名前と写真</li><li>自己紹介文</li><li>趣味や特技のリスト</li></ul><p>作成したコードを提出してください。</p>',
            'duration_minutes' => 60,
            'order' => 3,
            'points_reward' => 50,
            'is_published' => true,
        ]);

        // Create lessons for course 3
        Lesson::create([
            'course_id' => $course3->id,
            'title' => 'データ分析の重要性',
            'description' => 'なぜデータ分析が重要なのか、実例を交えて解説します。',
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration_minutes' => 12,
            'order' => 1,
            'points_reward' => 12,
            'is_published' => true,
        ]);

        Lesson::create([
            'course_id' => $course3->id,
            'title' => 'Excelの基本操作',
            'description' => 'データ入力、並べ替え、フィルターなどの基本操作を学びます。',
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration_minutes' => 25,
            'order' => 2,
            'points_reward' => 25,
            'is_published' => true,
        ]);

        // Create badges
        Badge::create([
            'name' => '初めての一歩',
            'description' => '最初のレッスンを完了する',
            'icon' => '🎯',
            'color' => '#10B981',
            'type' => 'first_lesson',
            'requirement_value' => 1,
        ]);

        Badge::create([
            'name' => '学習の習慣',
            'description' => '5つのレッスンを完了する',
            'icon' => '📚',
            'color' => '#3B82F6',
            'type' => 'lessons_count',
            'requirement_value' => 5,
        ]);

        Badge::create([
            'name' => '知識の探求者',
            'description' => '10個のレッスンを完了する',
            'icon' => '🔍',
            'color' => '#8B5CF6',
            'type' => 'lessons_count',
            'requirement_value' => 10,
        ]);

        Badge::create([
            'name' => '継続は力なり',
            'description' => '3日連続で学習する',
            'icon' => '🔥',
            'color' => '#EF4444',
            'type' => 'streak',
            'requirement_value' => 3,
        ]);

        Badge::create([
            'name' => '一週間の挑戦',
            'description' => '7日連続で学習する',
            'icon' => '💪',
            'color' => '#F59E0B',
            'type' => 'streak',
            'requirement_value' => 7,
        ]);

        Badge::create([
            'name' => 'ポイントハンター',
            'description' => '500ポイントを獲得する',
            'icon' => '⭐',
            'color' => '#FFD700',
            'type' => 'points',
            'requirement_value' => 500,
        ]);

        Badge::create([
            'name' => 'プログラミングマスター',
            'description' => '「はじめてのプログラミング」コースを完了する',
            'icon' => '🏆',
            'color' => '#6366F1',
            'type' => 'course_complete',
            'requirement_value' => 1,
            'course_id' => $course1->id,
        ]);

        Badge::create([
            'name' => 'Webデザイナー',
            'description' => '「Webデザイン基礎」コースを完了する',
            'icon' => '🎨',
            'color' => '#EC4899',
            'type' => 'course_complete',
            'requirement_value' => 1,
            'course_id' => $course2->id,
        ]);

        Badge::create([
            'name' => 'データアナリスト',
            'description' => '「データ分析入門」コースを完了する',
            'icon' => '📊',
            'color' => '#14B8A6',
            'type' => 'course_complete',
            'requirement_value' => 1,
            'course_id' => $course3->id,
        ]);

        Badge::create([
            'name' => '完璧主義者',
            'description' => 'クイズで満点を取る',
            'icon' => '💯',
            'color' => '#F43F5E',
            'type' => 'perfect_score',
            'requirement_value' => 1,
        ]);
    }
}
