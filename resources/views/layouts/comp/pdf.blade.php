<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

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
            src: url("{{ storage_path('fonts/ipaexg.ttf')}}") format('truetype');
        }
        
@charset "UTF-8";
html,body{
	height: 100%;
}
body {
    font-size: 14px;
    font-family: ipag;
    line-height: 1.4;
    -webkit-text-size-adjust: 100%;

    /* footar用 */ 
    display: flex;
    flex-direction: column;
}



/*/////////////////////////////
 common - テーブル 設定
/////////////////////////////*/
/* 共通 */
table {
    border-collapse: collapse;
}


/*/////////////////////////////
 footer
/////////////////////////////*/
.footer {
    width: 100%;
    max-width: 100%;
    padding: 8px 0;
    text-align: center;
}

/*/////////////////////////////
 contents - レイアウト
/////////////////////////////*/
.main {
    flex: 1 0 auto; /* footar用にコンテンツの高さを取る */ 
    margin-bottom: 15px;
}
.mainContents {
    display: flex;
    justify-content: space-between;
    width: 1200px;
    margin: auto;
}

/* 2カラム */
.mainContentsInner {
    width: 850px;
}

/* セクション ----------------- */
.secContents {
    background-color: #fff;
}

/*/////////////////////////////
 正式応募
/////////////////////////////*/
.tblCaption {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #B1B1B1;
}
.tblCaptionTitle {
    font-size: 20px;
}
.tblCaptionList {
    display: flex;
}
.tblCaptionList li:not(:last-child) {
    margin-right: 20px;
}
.tblCaptionList li a {
    font-size: 12px;
    text-decoration: underline;
}

.containerTblUserInfo.mb-ajust {
    margin-bottom: 50px;
}
.tblUserInfo  {
    width: 100%;
}
.tblUserInfo th {
    background-color: #F5F5F5;
    font-weight: normal;
    text-align: left;
}
.tblUserInfo td {
    margin-bottom: 10px;
}
.tblUserInfo th, .tblUserInfo td {
    padding: 10px 10px;
}

</style>

</head>
<body class="bg-admin">
    
    <main class="main">
        <div class="mainContents">
            @yield('content')
        </div>
    </main>
        
    <footer class="footer">
        <small>Copyright ARK All Rights Reserved.</small>
    </footer>


</body>
</html>

