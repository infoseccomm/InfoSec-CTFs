# 2048

To find the vulnerable endpoint you either need to lose in a game and see what request is being made or look at scoreboard.js.
You will see the request to https://infosec-2048.chals.io/score.php?score=44 which will reply with: {"message":"Your score is low. Better luck next time!"}
By increasing the score param you will receive other messages and when you set it high enough (e.g., https://infosec-2048.chals.io/score.php?score=44444444444444444) you will receive the flag.
