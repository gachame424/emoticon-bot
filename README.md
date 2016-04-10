# linebot-test

## これはなに？

LineBot をひとまず PHP on Heroku で動かしてみるためのミニマルなフレームワーク(スターターキット)です.
silex を使っています.

![screen image](https://raw.github.com/wiki/CoachUnited/linebot-test/images/linebot_image.png)

## 始め方

* LINE Bot API Trial account を取得してください.
* Heroku アカウントを取得してください.
* 下の Deploy ボタンを押してデプロイします. LINE Bot API アカウントの画面で確認できる各種パラメータ値を環境変数として指定してください.

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](http://bit.ly/linebot-test-deploy)

* LINE Bot Channel Dashboard で以下の値を設定します.
    * LINE からのコールバックURL: `<HerokuにデプロイされたURL>/callback`
    * LINE Bot API をアクセスするサーバのIPアドレスのホワイトリスト: https://dashboard.usefixie.com/#/account で確認できます.
* LINE クライアントから Bot に話しかけてみてください.
* web/index.php を編集して Hack してみましょう. Enjoy!
