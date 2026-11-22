<?php
require_once __DIR__ . '/config/config.php';
if (!isLoggedIn()) {
    header('Location: /login.php');
    exit();
}
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика | Яблокат</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .stats-page {
            min-height: 100vh;
            padding: 20px;
        }
        .stats-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto 3rem;
        }
        .stat-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        .stat-value {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
            display: block;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 1.1rem;
            color: var(--text-color);
            opacity: 0.8;
        }
        .games-history {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }
        .games-history h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        .game-item {
            border-bottom: 1px solid var(--primary-color);
            padding: 15px 0;
        }
        .game-item:last-child {
            border-bottom: none;
        }
        .game-result {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .winner-badge {
            background:
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .loser-badge {
            background:
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .back-button {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="theme-toggle">
        <button id="themeToggle" aria-label="Переключить тему">
            <span class="theme-icon">🌙</span>
        </button>
    </div>
    <div class="stats-page">
        <div class="stats-header">
            <h1>Статистика игрока</h1>
            <p>Привет, <strong><?php echo escape($user['username']); ?></strong>!</p>
        </div>
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                <span class="stat-label">Всего игр</span>
                <span class="stat-value" id="totalGames">0</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Побед</span>
                <span class="stat-value" id="totalWins">0</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Поражений</span>
                <span class="stat-value" id="totalLosses">0</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Лучший результат</span>
                <span class="stat-value" id="bestScore">-</span>
            </div>
        </div>
        <div class="games-history">
            <h2>История последних игр</h2>
            <div id="gamesHistory">
                <p>Загрузка...</p>
            </div>
        </div>
        <div class="back-button">
            <a href="/" class="btn btn-primary">Вернуться на главную</a>
        </div>
    </div>
    <script src="/js/theme.js"></script>
    <script>
        async function loadStats() {
            try {
                const response = await fetch('/api/get_stats.php');
                const data = await response.json();
                if (data.success) {
                    document.getElementById('totalGames').textContent = data.stats.total_games;
                    document.getElementById('totalWins').textContent = data.stats.total_wins;
                    document.getElementById('totalLosses').textContent = data.stats.total_losses;
                    document.getElementById('bestScore').textContent =
                        data.stats.best_score === 999 ? '-' : data.stats.best_score;
                    const historyDiv = document.getElementById('gamesHistory');
                    if (data.recentGames.length === 0) {
                        historyDiv.innerHTML = '<p>У вас пока нет сохраненных игр</p>';
                    } else {
                        historyDiv.innerHTML = data.recentGames.map(game => `
                            <div class="game-item">
                                <div class="game-result">
                                    <div>
                                        <strong>${game.player_name}</strong> vs <strong>${game.opponent_name}</strong>
                                    </div>
                                    <div>
                                        <span>${game.player_score} - ${game.opponent_score}</span>
                                    </div>
                                    <div>
                                        <span class="${game.is_winner ? 'winner-badge' : 'loser-badge'}">
                                            ${game.is_winner ? 'Победа' : 'Поражение'}
                                        </span>
                                    </div>
                                </div>
                                <div style="font-size: 0.9rem; opacity: 0.7; margin-top: 5px;">
                                    Сложность: ${game.difficulty} | Раундов: ${game.rounds_played} |
                                    ${new Date(game.played_at).toLocaleString('ru-RU')}
                                </div>
                            </div>
                        `).join('');
                    }
                }
            } catch (error) {
                console.error('Ошибка загрузки статистики:', error);
                document.getElementById('gamesHistory').innerHTML =
                    '<p>Ошибка загрузки данных</p>';
            }
        }
        loadStats();
    </script>
</body>
</html>