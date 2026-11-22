<?php
require_once __DIR__ . '/config/config.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Яблокат - Игра</title>
    <meta name="description" content="Яблокат - увлекательная игра, где игроки запускают яблоки на цель">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="theme-toggle">
        <button id="themeToggle" aria-label="Переключить тему">
            <span class="theme-icon">🌙</span>
        </button>
    </div>
    <div id="mainPage" class="page active">
        <div class="hero">
            <h1 class="main-title">ЯБЛОКАТ</h1>
            <div class="banner">
                <img src="/yablokat.jpg" alt="Яблокат" class="banner-image">
            </div>
            <div class="main-buttons">
                <button id="startGameBtn" class="btn btn-large btn-primary">Начать играть</button>
                <button id="rulesBtn" class="btn btn-large btn-secondary">Правила</button>
            </div>
            <div class="auth-buttons">
                <?php if ($user): ?>
                    <span class="user-welcome">Привет, <?php echo escape($user['username']); ?>!</span>
                    <a href="/stats.php" class="btn btn-small">Статистика</a>
                    <a href="/api/logout.php" class="btn btn-small">Выйти</a>
                <?php else: ?>
                    <a href="/register.php" class="btn btn-small">Зарегистрироваться</a>
                    <a href="/login.php" class="btn btn-small">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="loadingAnimation" class="loading-overlay">
        <div class="apple-loader">
            <svg viewBox="0 0 200 200" class="apple-svg">
                <defs>
                    <linearGradient id="appleGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff4757;stop-opacity:1" />
                        <stop offset="50%" style="stop-color:#ee5a6f;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#c23616;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="leafGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#6ab04c;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#4a7c3a;stop-opacity:1" />
                    </linearGradient>
                    <radialGradient id="shineGradient" cx="30%" cy="30%">
                        <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.8" />
                        <stop offset="100%" style="stop-color:#ffffff;stop-opacity:0" />
                    </radialGradient>
                </defs>
                <g id="appleGroup">
                    <ellipse cx="100" cy="100" rx="45" ry="52" fill="url(#appleGradient)" stroke="#8b0000" stroke-width="2"/>
                    <ellipse cx="100" cy="95" rx="42" ry="48" fill="url(#appleGradient)"/>
                    <ellipse cx="75" cy="75" rx="20" ry="25" fill="url(#shineGradient)" opacity="0.6"/>
                    <path d="M95,48 Q90,35 85,30 Q80,25 75,25 Q70,30 75,35 Q80,38 85,40"
                          fill="url(#leafGradient)" stroke="#2d5016" stroke-width="1.5"/>
                    <path d="M100,48 Q100,35 100,25"
                          stroke="#5d4037" stroke-width="3" fill="none" stroke-linecap="round"/>
                </g>
                <g id="appleFillGroup" opacity="0">
                    <ellipse cx="100" cy="100" rx="45" ry="52" fill="url(#appleGradient)" stroke="#8b0000" stroke-width="2"/>
                    <ellipse cx="100" cy="95" rx="42" ry="48" fill="url(#appleGradient)"/>
                    <ellipse cx="75" cy="75" rx="20" ry="25" fill="url(#shineGradient)" opacity="0.6"/>
                    <path d="M95,48 Q90,35 85,30 Q80,25 75,25 Q70,30 75,35 Q80,38 85,40"
                          fill="url(#leafGradient)" stroke="#2d5016" stroke-width="1.5"/>
                    <path d="M100,48 Q100,35 100,25"
                          stroke="#5d4037" stroke-width="3" fill="none" stroke-linecap="round"/>
                </g>
            </svg>
            <div class="loading-text">Загрузка...</div>
        </div>
    </div>
    <div id="gameMenu" class="page">
        <div class="game-menu-container">
            <h2>Настройки игры</h2>
            <div class="difficulty-section">
                <label>Выберите уровень сложности:</label>
                <select id="difficultySelect" class="form-control">
                    <option value="Новичок">Новичок</option>
                    <option value="Профи">Профи</option>
                    <option value="Хакер">Хакер</option>
                    <option value="Яблокатер">Яблокатер</option>
                </select>
                <p class="difficulty-description">
                    Чем выше сложность, тем дальше стартовая позиция игрока от статичного яблока-цели
                </p>
            </div>
            <div class="players-section">
                <h3>Игроки</h3>
                <div class="form-group">
                    <label for="player1Name">Игрок 1:</label>
                    <input type="text" id="player1Name" class="form-control" placeholder="Имя игрока 1" required>
                </div>
                <div class="form-group">
                    <label for="player2Name">Игрок 2:</label>
                    <input type="text" id="player2Name" class="form-control" placeholder="Имя игрока 2" required>
                </div>
            </div>
            <div id="firstPlayerInfo" class="first-player-info"></div>
            <div class="menu-buttons">
                <button id="startPlayingBtn" class="btn btn-primary">Начать игру</button>
                <button id="backToMainBtn" class="btn btn-secondary">Назад</button>
            </div>
        </div>
    </div>
    <div id="gameScreen" class="page">
        <div class="game-container">
            <div class="game-header">
                <div class="current-player">
                    Ход: <span id="currentPlayerName"></span>
                </div>
                <div class="game-stats">
                    <div class="player-score">
                        <span id="player1NameDisplay"></span>: <span id="player1Score">0</span>
                    </div>
                    <div class="player-score">
                        <span id="player2NameDisplay"></span>: <span id="player2Score">0</span>
                    </div>
                </div>
            </div>
            <div class="game-board">
                <div class="round-info">Раунд <span id="roundNumber">1</span></div>
            </div>
            <div class="game-controls">
                <h4>Начислить очки:</h4>
                <div class="score-buttons">
                    <button class="btn btn-score" data-points="1">+1 балл</button>
                    <button class="btn btn-score" data-points="2">+2 балла</button>
                    <button class="btn btn-score" data-points="3">+3 балла</button>
                </div>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <button id="nextTurnBtn" class="btn btn-primary">Следующий</button>
                    <button id="endGameBtn" class="btn btn-secondary">Завершить игру</button>
                </div>
            </div>
        </div>
    </div>
    <div id="resultsScreen" class="page">
        <div class="results-container">
            <h2>Игра окончена!</h2>
            <div id="resultsContent"></div>
            <div class="results-buttons">
                <button id="saveResultBtn" class="btn btn-primary" style="display: none;">Сохранить результат</button>
                <button id="playAgainBtn" class="btn btn-secondary">Играть снова</button>
                <button id="backToMainFromResultsBtn" class="btn btn-secondary">На главную</button>
            </div>
        </div>
    </div>
    <script src="/js/theme.js"></script>
    <script src="/js/game.js"></script>
</body>
</html>