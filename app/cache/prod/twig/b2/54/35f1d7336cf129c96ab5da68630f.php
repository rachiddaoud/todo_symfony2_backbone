<?php

/* TodoBundle::layout.html.twig */
class __TwigTemplate_b25435f1d7336cf129c96ab5da68630f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
<title>TODO</title>

<meta charset=\"UTF-8\" />
<link rel=\"icon\" type=\"image/png\" href=\"http://localhost/todo/web/todolist.png\" />
<link rel=\"stylesheet\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/todo/css/reset.css"), "html", null, true);
        echo "\"/>
<link rel=\"stylesheet\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/todo/css/style.css"), "html", null, true);
        echo "\"/>
<link rel=\"stylesheet\" href=\"http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css\" />

</head>
<body>
\t<div id=\"view\">
\t</div>
\t<script type=\"text/html\" id=\"template-app\">
\t\t<div class=\"form-wrapper  cf\">
\t\t\t<input name=\"todo\" type=\"text\"/>
\t\t\t<button type=\"button\">Do it</button>
\t\t</div>
\t\t<div id=\"orderby\">
\t\tOrder by : <span id=\"sortByPriority\">Priority</span> / <span id=\"sortByDate\">Date</span>
\t\t</div>
\t\t<div id=\"todolist\">
\t\t\t<div id=\"todolist-1\">
\t\t  \t</div>
\t\t\t<div id=\"todolist-2\">
\t\t  \t</div>
\t\t\t<div id=\"todolist-3\">
\t\t  \t</div>
\t\t</div>
\t</script>
    <script type=\"text/html\" id=\"template-todo-item\">
\t\t<div class='todoitem'>
\t\t\t<input type=\"checkbox\" <% if(completed) {  %> checked=\"checked\" <%  }  %> />
\t\t    <strong class='todoitemtitle  <% if(completed) {  %> barred <%  }  %>'><%= title %> </strong>
\t  \t</div>
\t</script>
    <script type=\"text/html\" id=\"template-changetodo-item\">
\t\t<div class=\"biglayer\">
\t\t\t<form id='login-form'>
\t\t\t\t<h1>Update your TODO</h1>
\t\t\t\t<div class=\"error\"></div>
\t\t\t\t<div class=\"success\"></div>
\t\t\t\t<input type=\"text\" name=\"title\" value=\"<%= title %>\"/>

\t\t\t\t<input type=\"text\" name=\"duedate\" value=\"<%= duedate %>\"/><br/>

\t\t\t\t<div class=\"radioinput\">
\t\t\t\t\t<input type=\"radio\" id=\"radio1\" name=\"priority\" value=\"0\" <% if(priority==0) { %>checked=\"checked\" <% } %>/><label for=\"radio1\">High</label>
\t\t\t\t\t<input type=\"radio\" id=\"radio2\" name=\"priority\" value=\"1\" <% if(priority==1) { %>checked=\"checked\" <% } %> /><label for=\"radio2\">Medium</label>
\t\t\t\t\t<input type=\"radio\" id=\"radio3\" name=\"priority\" value=\"2\" <% if(priority==2) { %>checked=\"checked\" <% } %>/><label for=\"radio3\">Low</label>
\t\t\t\t</div>
\t\t\t\t<button class=\"login\" type=\"button\">Submit</button>
\t\t  \t</form>
\t  \t</div>
\t</script>
    <script type=\"text/html\" id=\"template-login-screen\">
    \t<div id=\"login-screen\" class=\"biglayer\">
\t\t\t<form id='login-form'>
\t\t\t\t<h1>Register or Login</h1>
\t\t\t\t<div class=\"error\"></div>
\t\t\t\t<div class=\"success\"></div>
\t\t\t\t<div class=\"input-container\"> 
\t\t\t\t\t<input type=\"text\" name=\"username\" placeholder=\"Username\"/>
\t\t\t\t</div>
\t\t\t\t<div class=\"input-container\"> 
\t\t\t\t\t<input type=\"password\" name=\"password\" placeholder=\"Password\"/>
\t\t\t\t</div>
\t\t\t\t<button class=\"register\" type=\"button\">Register</button>
\t\t\t\t<button class=\"login\" type=\"button\">Login</button>
\t\t  \t</form>
\t  \t</div>
\t</script>
\t<div style=\"position:relative;bottom:-80px;text-align:right;color:#6B6B6B;font-weight: bold;\">Created By DAOUD M.Rachid.</div>
    <script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
  \t<script src=\"http://code.jquery.com/ui/1.10.3/jquery-ui.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 78
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/todo/js/jquery.cookie.js"), "html", null, true);
        echo "\"></script>
\t
    <script src=\"http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.1/underscore-min.js\"></script>
    <script src=\"http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 82
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/todo/js/main.js"), "html", null, true);
        echo "\"></script>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "TodoBundle::layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  111 => 82,  104 => 78,  32 => 9,  28 => 8,  19 => 1,);
    }
}
