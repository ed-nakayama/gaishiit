<html lang="ja">
    <head>
        <title>pdf output test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @font-face{
                font-family: ipag;
                font-style: normal;
                font-weight: normal;
                src: url("{{ storage_path('fonts/ipaexg.ttf')}}") format('truetype');
            }
            @font-face{
                font-family: ipag;
                font-style: bold;
                font-weight: bold;
                src: url("{{ storage_path('fonts/ipaexg.ttf.ttf')}}") format('truetype');
            }
            body {
                font-family: ipag;
                line-height: 80%;
            }

        </style>
    </head>
    <body>

      PDFの出力テスト！<br>
        <div style="font-weight:bold">ここは太字！</div>
        お寿司のテーブル
        <table class="sushiTable">
            <tr>
                <th>名前</th>
                <th>価格</th>
            </tr>
        </table>
    </body>
</html>    

