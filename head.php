<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <meta name="autor" content="Nicolás Picaguá">
        <link rel="shortcut icon" href="assets/img/favicon.ico " />
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datepicker/datepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/chosen/css/chosen.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />

        <!-- <link href="assets/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" /> -->
<!-- ****************************************************************** -->
<script language="javascript">
      function getkey(e)
      {
        if (window.event)
          return window.event.keyCode;
        else if (e)
          return e.which;
        else
          return null;
      }
      function goodchars(e, goods, field)
      {
        var key, keychar;
        key = getkey(e);
        if (key == null) return true;
       
        keychar = String.fromCharCode(key);
        keychar = keychar.toLowerCase();
        goods = goods.toLowerCase();
       
        // check goodkeys
        if (goods.indexOf(keychar) != -1)
            return true;
        // control keys
        if ( key==null || key==0 || key==8 || key==9 || key==27 )
          return true;
          
        if (key == 13) {
          var i;
          for (i = 0; i < field.form.elements.length; i++)
            if (field == field.form.elements[i])
              break;
          i = (i + 1) % field.form.elements.length;
          field.form.elements[i].focus();
          return false;
        };
        // else return false
        return false;
      }
      </script>
<!-- ****************************************************************** -->
    </head>