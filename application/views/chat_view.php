<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<title>Websockets Chat</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Portfolio project showing skills with Node.js, PHP, Javascript, jQuery, AJAX and API's">
	<meta name="author" content="Patrick Burns">
        <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="assets/css/jquery.jgrowl.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            if (window.location.hash && window.location.hash == '#_=_') {
                if (window.history && history.pushState) {
                    window.history.pushState("", document.title, window.location.pathname);
                } else {
                    // Prevent scrolling by storing the page's current scroll offset
                    var scroll = {
                        top: document.body.scrollTop,
                        left: document.body.scrollLeft
                    };
                    window.location.hash = '';
                    // Restore the scroll offset, should be flicker free
                    document.body.scrollTop = scroll.top;
                    document.body.scrollLeft = scroll.left;
                }
            }
        </script>
</head>
<body>
    <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Websockets Chat</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="#">Source</a></li>
            <li><a href="http://www.linkedin.com/in/burnsforce">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
              <ul class="dropdown-menu">
                    <li><a href="http://simpleurl.co">URL Shortener</a></li>
                    <li><a href="http://burnsforcedevelopment.com/linkbaitgenerator/">Link Bait Generator</a></li>
                    <li><a href="http://chat.burnsforcedevelopment.com/">Web Sockets Chat</a></li>
                    <li><a href="http://burnsforcedevelopment.com/citysay/">Kansas City Says</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
              <?php 
                    if($this->session->userdata('logged_in') != 1){
                        printf('<li><a href="/register/" data-toggle="modal">Register</a></li>');
                        printf('<li><a href="/login/" data-toggle="modal">Login</a></li>');
                    }
                    else{
                        //$avatarLink = '<img src="http://graph.facebook.com/' . $this->session->userdata('username') . '/picture" class="header-avatar" />';
                        $avatarLink = '<img src="' . $this->session->userdata('avatar') . '" class="header-avatar" />';
                        printf('<li class="dropdown">');
                        printf('<a href="#" class="dropdown-toggle" data-toggle="dropdown">');
                        printf('<span>%s %s</span>', $avatarLink, $this->session->userdata('username'));
                        printf('</a>');
                        printf('<ul class="dropdown-menu">');
                        printf('<li><a href="/user"><i class="icon-user"></i> Profile</a></li>');
                        printf('<li class="divider"></li>');
                        printf('<li><a href="/logout"><i class="icon-off"></i> Logout</a></li>');
                        printf('</ul>');
                        printf('</li>');
                    }
                  ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
    <div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Chat
<!--                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                        <ul class="dropdown-menu slidedown">
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-refresh">
                            </span>Refresh</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-ok-sign">
                            </span>Available</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-remove">
                            </span>Busy</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-time"></span>
                                Away</a></li>
                            <li class="divider"></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-off"></span>
                                Sign Out</a></li>
                        </ul>
                    </div>-->
                </div>
                <div class="panel-body">
                    <input type="hidden" id="pageNum" value="1" />
                        <?php 
                            if($this->session->userdata('logged_in') != 1){
                                printf('<input type="hidden" id="loggedIn" value="false" />');
                            }
                            else{
                                printf('<input type="hidden" id="loggedIn" value="true" />');
                                printf('<input type="hidden" id="userName" value="%s" />', $this->session->userdata('username'));
                                printf('<input type="hidden" id="avatar" value="%s" />', $this->session->userdata('avatar'));
                            }
                        ?>
                    <ul class="chat">
                        <?php 
                            foreach($messageData as $message){
                                printf('%s', $message['messageHtml']);
                            }
                        ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div id="messageInputGroup" class="input-group">
                        <input id="message" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button id="submitNewMessage" class="btn btn-warning btn-sm has-spinner"><span class="spinner"><i class="fa fa-refresh fa-spin"></i></span>Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-user"></span> Who's Online
                </div>
                <div class="panel-body">
                    <ul class="user-list">
<!--                       <li id="loadingMessage" class="left clearfix">
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <h5 style="text-align:center;"><i class="fa fa-refresh fa-spin"></i> Loading User List</h5>
                                </div>
                            </div>
                        </li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="/assets/js/plugins/bootstrap.min.js"></script>
<script src="/nodejs/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.min.js"></script>
<script src="assets/js/plugins/jquery.jgrowl.min.js"></script>
<script src="/assets/js/plugins/jquery.timeago.js"></script>
<script src="/assets/js/custom/chat.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47186422-1', 'burnsforcedevelopment.com');
  ga('send', 'pageview');

</script>
</html>