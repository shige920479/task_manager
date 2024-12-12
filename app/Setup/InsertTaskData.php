<?php
namespace App\Setup;

class InsertTaskData extends Database
{
  public static function insertTasks()
  {
    try {
      $pdo = self::dbCon();
  
      $sql = "ALTER TABLE member auto_increment = 1";
      $pdo->query($sql);
  
      $sql = "INSERT INTO task (member_id, priority, category, theme, content, deadline) VALUES
              (1, 3, '開発', '機能追加A', '新しいレポート機能を追加するタスクです。', '2024-11-12 10:00:00'),
              (1, 2, 'マーケティング', 'キャンペーン準備', '次回のプロモーション準備を進めるタスクです。', '2024-11-18 15:00:00'),
              (1, 1, '会議', '週次ミーティング', 'チーム内で進捗を共有するための会議です。', '2024-12-03 09:00:00'),
              (1, 3, 'レビュー', 'コードレビュー', '新しいモジュールのコードレビューを行います。', '2024-12-05 14:00:00'),
              (1, 1, '報告書作成', '四半期報告書', '四半期報告書を作成し、上司に提出するタスクです。', '2024-12-20 17:00:00'),
              (1, 2, '開発', 'バグ修正A', '重要な機能のバグを修正します。', '2024-12-16 12:00:00'),
              (1, 3, 'マーケティング', 'SEO改善', 'サイトのSEOを改善します。', '2024-11-25 11:00:00'),
              (1, 1, '会議', 'クライアント会議', '主要クライアントとの進捗会議です。', '2024-12-06 16:00:00'),
              (1, 2, 'レビュー', 'ドキュメントレビュー', '技術ドキュメントのレビューを行います。', '2024-12-13 14:00:00'),
              (1, 1, '開発', 'パフォーマンス改善A', '既存のコードのパフォーマンスを向上させます。', '2024-12-27 10:00:00'),
              (2, 2, '開発', 'システムテストB', '既存機能のシステムテストを実施します。', '2024-11-15 11:00:00'),
              (2, 3, '会議', '月次レビュー', '月次の業務レビューを実施します。', '2024-11-25 13:00:00'),
              (2, 1, 'マーケティング', 'SNS広告分析', '最新のSNS広告の効果を分析します。', '2024-12-05 10:00:00'),
              (2, 2, 'レビュー', '設計レビュー', '新しい設計方針についてチームレビューを行います。', '2024-12-16 16:00:00'),
              (2, 1, '開発', 'パフォーマンス改善B', 'システムのパフォーマンスを最適化します。', '2024-12-26 18:00:00'),
              (2, 3, '開発', '新規機能検討', '新しい製品機能を検討します。', '2024-11-22 10:00:00'),
              (2, 2, 'マーケティング', 'メールマーケティング', '新規顧客向けメールマーケティングを行います。', '2024-11-29 14:00:00'),
              (2, 1, '会議', '開発戦略会議', '次年度の開発戦略を議論するための会議です。', '2024-12-09 15:30:00'),
              (2, 3, 'レビュー', '顧客フィードバック', '顧客のフィードバックを取りまとめ、レビューします。', '2024-12-20 11:00:00'),
              (2, 1, '開発', 'UI/UX改善', '製品のUI/UXを向上させます。', '2024-12-25 13:30:00'),
              (3, 2, '開発', 'バグ修正C', '小規模なバグを修正するタスクです。', '2024-11-20 09:30:00'),
              (3, 3, '会議', 'プロジェクト会議', '主要プロジェクトの進捗状況を共有します。', '2024-12-17 14:00:00'),
              (3, 1, 'マーケティング', '市場調査A', '競合製品の市場調査を行います。', '2024-12-05 13:00:00'),
              (3, 2, 'レビュー', 'UIデザインレビュー', '新しいUIデザイン案をレビューします。', '2024-12-10 11:00:00'),
              (3, 1, '開発', 'パフォーマンス改善C', 'バックエンド処理の高速化を実施します。', '2024-12-23 16:00:00'),
              (3, 3, '開発', '機能仕様書作成', '新機能の仕様書を作成します。', '2024-11-25 10:00:00'),
              (3, 2, 'マーケティング', '広告キャンペーン検討', '新しい広告キャンペーンを企画します。', '2024-12-02 15:00:00'),
              (3, 1, '会議', '技術検討会', '技術課題の解決策を議論します。', '2024-12-10 09:30:00'),
              (3, 3, 'レビュー', '進捗確認', 'プロジェクトの進捗を確認するタスクです。', '2024-12-18 14:00:00'),
              (3, 1, '開発', 'API改善', '既存APIのパフォーマンスを最適化します。', '2024-12-30 12:00:00'),
              (4, 1, 'マーケティング', '市場分析B', 'ターゲット市場の新規ニーズを分析します。', '2024-11-15 10:00:00'),
              (4, 2, '開発', '新機能プロトタイプ', '新しい機能のプロトタイプを作成します。', '2024-11-27 14:00:00'),
              (4, 3, '会議', '顧客サポート会議', '顧客対応の現状を改善するための会議です。', '2024-12-02 16:00:00'),
              (4, 1, 'レビュー', '製品レビュー', '新製品のフィードバックをレビューします。', '2024-12-06 11:00:00'),
              (4, 2, '開発', 'データベース最適化', 'データベースのクエリ最適化を実施します。', '2024-12-13 09:30:00'),
              (4, 3, 'マーケティング', 'コンテンツ作成', '新しいコンテンツを作成します。', '2024-12-20 14:30:00'),
              (4, 2, '会議', 'セキュリティ会議', '製品のセキュリティ強化策を議論します。', '2024-12-27 15:00:00'),
              (4, 1, 'レビュー', 'コード品質向上', 'コード品質の向上を目指したレビューを実施します。', '2024-12-30 10:00:00'),
              (4, 3, '開発', 'ログシステム改善', 'ログシステムの機能を強化します。', '2024-12-18 11:30:00'),
              (4, 1, '開発', 'ユーザー管理機能', 'ユーザー管理機能を追加します。', '2024-12-31 12:00:00'),
              (5, 3, '開発', 'API拡張', '新規サービス向けにAPIを拡張します。', '2024-11-18 14:00:00'),
              (5, 2, 'マーケティング', '調査データ分析', '顧客調査データを分析します。', '2024-11-25 10:00:00'),
              (5, 1, '会議', 'ステークホルダー会議', 'プロジェクトの主要ステークホルダーとの会議です。', '2024-12-03 15:30:00'),
              (5, 3, 'レビュー', 'セキュリティ監査', 'システムのセキュリティを監査します。', '2024-12-09 16:00:00'),
              (5, 1, '開発', 'クラウド移行', 'システムのクラウド環境への移行を進めます。', '2024-12-17 13:00:00'),
              (5, 2, '開発', 'リファクタリング', '既存コードのリファクタリングを実施します。', '2024-12-24 11:00:00'),
              (5, 3, 'マーケティング', '動画広告作成', '新しい動画広告を作成します。', '2024-12-25 17:00:00'),
              (5, 2, '会議', '開発進捗会議', '開発チームの進捗状況を確認します。', '2024-12-17 09:00:00'),
              (5, 1, 'レビュー', 'UIテストレビュー', '新しいUIテストの結果をレビューします。', '2024-12-30 12:00:00'),
              (5, 3, '開発', '機能改善', '既存機能の改善を進めます。', '2024-12-31 15:00:00'),
              (1, 2, 'マーケティング', 'キャンペーン企画B', '新規キャンペーンの立案を行います。', '2024-11-20 14:00:00'),
              (1, 1, '開発', 'ログシステム改良', 'ログ管理システムの改良を進めます。', '2024-11-28 09:30:00'),
              (1, 3, '会議', '技術戦略会議', '次年度の技術戦略を議論します。', '2024-12-05 13:30:00'),
              (1, 1, 'レビュー', 'コードレビュー', '新規コードのレビューを行います。', '2024-12-12 11:00:00'),
              (1, 2, '開発', '新機能デプロイ', '新機能のデプロイを実施します。', '2024-12-25 16:00:00'),
              (2, 1, 'マーケティング', '市場予測分析', '市場の動向を予測する分析を行います。', '2024-11-22 10:00:00'),
              (2, 2, '開発', 'デバッグセッション', '主要バグのデバッグを進めます。', '2024-11-29 14:30:00'),
              (2, 3, '会議', '年間計画レビュー', '年間計画の最終レビューを行います。', '2024-12-04 15:00:00'),
              (2, 1, 'レビュー', 'セキュリティレビュー', 'システムのセキュリティを確認します。', '2024-12-11 09:00:00'),
              (2, 2, '開発', 'データマイグレーション', 'データ移行作業を実施します。', '2024-12-20 13:00:00'),
              (3, 1, 'マーケティング', 'コンテンツ戦略立案', '新しいコンテンツ戦略を立案します。', '2024-11-18 11:00:00'),
              (3, 3, '会議', 'プロジェクト振り返り', 'プロジェクトの振り返りと教訓を共有します。', '2024-11-26 16:00:00'),
              (3, 2, '開発', 'バックエンド最適化', 'バックエンドの処理を最適化します。', '2024-12-09 12:00:00'),
              (3, 1, 'レビュー', 'デザインレビュー', '新しいデザインのフィードバックを収集します。', '2024-12-18 10:30:00'),
              (3, 2, '開発', 'インフラ改善', 'サーバーインフラのパフォーマンスを向上させます。', '2024-12-30 14:30:00'),
              (4, 2, '開発', '新規プロジェクト開始', '新規プロジェクトの開始準備を行います。', '2024-11-25 10:30:00'),
              (4, 3, '会議', '社内勉強会', '技術知識を共有する社内勉強会を実施します。', '2024-11-29 17:00:00'),
              (4, 1, 'マーケティング', 'キャンペーンレビュー', '広告キャンペーンの結果をレビューします。', '2024-12-10 14:00:00'),
              (4, 2, 'レビュー', 'プロトタイプレビュー', '新しいプロトタイプをレビューします。', '2024-12-16 11:30:00'),
              (4, 3, '開発', 'モバイル最適化', 'モバイルアプリの最適化を行います。', '2024-12-27 16:00:00'),
              (5, 3, '開発', 'キャッシュ最適化', 'アプリケーションのキャッシュシステムを最適化します。', '2024-11-21 13:00:00'),
              (5, 2, 'マーケティング', 'ユーザーアンケート分析', 'ユーザーアンケートの結果を分析します。', '2024-12-02 15:30:00'),
              (5, 1, '会議', '経営陣ブリーフィング', '経営陣向けにプロジェクト進捗を報告します。', '2024-12-09 09:00:00'),
              (5, 2, 'レビュー', 'QAテストレビュー', '品質保証テストの結果をレビューします。', '2024-12-18 10:00:00'),
              (5, 3, '開発', '新サービス開発', '新しいサービスの開発を開始します。', '2024-12-30 12:30:00')";
      $pdo->query($sql);
      $pdo = null;
      echo "taskデータの挿入に成功しました<br>";

    } catch(\PDOException $e) {
      echo "taskデータの挿入に失敗しました<br>";
      echo $e->getMessage();
      exit;
    }
  }
}