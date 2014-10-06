<?php

/* core/themes/bartik/templates/page.html.twig */
class __TwigTemplate_f9202de16fd7b55fe60cbe3294ee699c6f74c686f74f64f14679eee09ed17ba6 extends Twig_Template
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
        // line 79
        echo "<div id=\"page-wrapper\"><div id=\"page\">

  <header id=\"header\" role=\"banner\" aria-label=\"";
        // line 81
        echo twig_render_var(t("Site header"));
        echo "\"><div class=\"section clearfix\">

    ";
        // line 83
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "secondary_menu"), "html", null, true);
        echo "

    ";
        // line 85
        if ((isset($context["logo"]) ? $context["logo"] : null)) {
            // line 86
            echo "      <a href=\"";
            echo twig_drupal_escape_filter($this->env, (isset($context["front_page"]) ? $context["front_page"] : null), "html", null, true);
            echo "\" title=\"";
            echo twig_render_var(t("Home"));
            echo "\" rel=\"home\" id=\"logo\">
        <img src=\"";
            // line 87
            echo twig_drupal_escape_filter($this->env, (isset($context["logo"]) ? $context["logo"] : null), "html", null, true);
            echo "\" alt=\"";
            echo twig_render_var(t("Home"));
            echo "\" />
      </a>
    ";
        }
        // line 90
        echo "
    ";
        // line 91
        if (((isset($context["site_name"]) ? $context["site_name"] : null) || (isset($context["site_slogan"]) ? $context["site_slogan"] : null))) {
            // line 92
            echo "      <div id=\"name-and-slogan\"";
            if (((isset($context["hide_site_name"]) ? $context["hide_site_name"] : null) && (isset($context["hide_site_slogan"]) ? $context["hide_site_slogan"] : null))) {
                echo " class=\"visually-hidden\"";
            }
            echo ">
        ";
            // line 93
            if ((isset($context["site_name"]) ? $context["site_name"] : null)) {
                // line 94
                echo "          ";
                if ((isset($context["title"]) ? $context["title"] : null)) {
                    // line 95
                    echo "            <div id=\"site-name\"";
                    if ((isset($context["hide_site_name"]) ? $context["hide_site_name"] : null)) {
                        echo " class=\"visually-hidden\"";
                    }
                    echo ">
              <strong>
                <a href=\"";
                    // line 97
                    echo twig_drupal_escape_filter($this->env, (isset($context["front_page"]) ? $context["front_page"] : null), "html", null, true);
                    echo "\" title=\"";
                    echo twig_render_var(t("Home"));
                    echo "\" rel=\"home\"><span>";
                    echo twig_drupal_escape_filter($this->env, (isset($context["site_name"]) ? $context["site_name"] : null), "html", null, true);
                    echo "</span></a>
              </strong>
            </div>
          ";
                    // line 101
                    echo "          ";
                } else {
                    // line 102
                    echo "            <h1 id=\"site-name\"";
                    if ((isset($context["hide_site_name"]) ? $context["hide_site_name"] : null)) {
                        echo " class=\"visually-hidden\" ";
                    }
                    echo ">
              <a href=\"";
                    // line 103
                    echo twig_drupal_escape_filter($this->env, (isset($context["front_page"]) ? $context["front_page"] : null), "html", null, true);
                    echo "\" title=\"";
                    echo twig_render_var(t("Home"));
                    echo "\" rel=\"home\"><span>";
                    echo twig_drupal_escape_filter($this->env, (isset($context["site_name"]) ? $context["site_name"] : null), "html", null, true);
                    echo "</span></a>
            </h1>
          ";
                }
                // line 106
                echo "        ";
            }
            // line 107
            echo "
        ";
            // line 108
            if ((isset($context["site_slogan"]) ? $context["site_slogan"] : null)) {
                // line 109
                echo "          <div id=\"site-slogan\"";
                if ((isset($context["hide_site_slogan"]) ? $context["hide_site_slogan"] : null)) {
                    echo " class=\"visually-hidden\"";
                }
                echo ">
            ";
                // line 110
                echo twig_drupal_escape_filter($this->env, (isset($context["site_slogan"]) ? $context["site_slogan"] : null), "html", null, true);
                echo "
          </div>
        ";
            }
            // line 113
            echo "      </div><!-- /#name-and-slogan -->
    ";
        }
        // line 115
        echo "
    ";
        // line 116
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header"), "html", null, true);
        echo "
    ";
        // line 117
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "primary_menu"), "html", null, true);
        echo "

  </div></header> <!-- /.section, /#header-->

  ";
        // line 121
        if ((isset($context["messages"]) ? $context["messages"] : null)) {
            // line 122
            echo "    <div id=\"messages\"><div class=\"section clearfix\">
      ";
            // line 123
            echo twig_drupal_escape_filter($this->env, (isset($context["messages"]) ? $context["messages"] : null), "html", null, true);
            echo "
    </div></div> <!-- /.section, /#messages -->
  ";
        }
        // line 126
        echo "
  ";
        // line 127
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "featured")) {
            // line 128
            echo "    <aside id=\"featured\"><div class=\"section clearfix\">
      ";
            // line 129
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "featured"), "html", null, true);
            echo "
    </div></aside> <!-- /.section, /#featured -->
  ";
        }
        // line 132
        echo "
  <div id=\"main-wrapper\" class=\"clearfix\"><div id=\"main\" class=\"clearfix\">
    ";
        // line 134
        echo twig_drupal_escape_filter($this->env, (isset($context["breadcrumb"]) ? $context["breadcrumb"] : null), "html", null, true);
        echo "

    <main id=\"content\" class=\"column\" role=\"main\"><section class=\"section\">
      ";
        // line 137
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "highlighted")) {
            echo "<div id=\"highlighted\">";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "highlighted"), "html", null, true);
            echo "</div>";
        }
        // line 138
        echo "      <a id=\"main-content\" tabindex=\"-1\"></a>
      ";
        // line 139
        echo twig_drupal_escape_filter($this->env, (isset($context["title_prefix"]) ? $context["title_prefix"] : null), "html", null, true);
        echo "
        ";
        // line 140
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 141
            echo "          <h1 class=\"title\" id=\"page-title\">
            ";
            // line 142
            echo twig_drupal_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
            echo "
          </h1>
        ";
        }
        // line 145
        echo "      ";
        echo twig_drupal_escape_filter($this->env, (isset($context["title_suffix"]) ? $context["title_suffix"] : null), "html", null, true);
        echo "
        ";
        // line 146
        if ((isset($context["tabs"]) ? $context["tabs"] : null)) {
            // line 147
            echo "          <nav class=\"tabs\" role=\"navigation\" aria-label=\"";
            echo twig_render_var(t("Tabs"));
            echo "\">
            ";
            // line 148
            echo twig_drupal_escape_filter($this->env, (isset($context["tabs"]) ? $context["tabs"] : null), "html", null, true);
            echo "
          </nav>
        ";
        }
        // line 151
        echo "      ";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "help"), "html", null, true);
        echo "
        ";
        // line 152
        if ((isset($context["action_links"]) ? $context["action_links"] : null)) {
            // line 153
            echo "          <ul class=\"action-links\">
            ";
            // line 154
            echo twig_drupal_escape_filter($this->env, (isset($context["action_links"]) ? $context["action_links"] : null), "html", null, true);
            echo "
          </ul>
        ";
        }
        // line 157
        echo "      ";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content"), "html", null, true);
        echo "
      ";
        // line 158
        echo twig_drupal_escape_filter($this->env, (isset($context["feed_icons"]) ? $context["feed_icons"] : null), "html", null, true);
        echo "
    </section></main> <!-- /.section, /#content -->

    ";
        // line 161
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first")) {
            // line 162
            echo "      <div id=\"sidebar-first\" class=\"column sidebar\"><aside class=\"section\">
        ";
            // line 163
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first"), "html", null, true);
            echo "
      </aside></div><!-- /.section, /#sidebar-first -->
    ";
        }
        // line 166
        echo "
    ";
        // line 167
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second")) {
            // line 168
            echo "      <div id=\"sidebar-second\" class=\"column sidebar\"><aside class=\"section\">
        ";
            // line 169
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second"), "html", null, true);
            echo "
      </aside></div><!-- /.section, /#sidebar-second -->
    ";
        }
        // line 172
        echo "
  </div></div><!-- /#main, /#main-wrapper -->

  ";
        // line 175
        if ((($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "triptych_first") || $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "triptych_middle")) || $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "triptych_last"))) {
            // line 176
            echo "    <div id=\"triptych-wrapper\"><aside id=\"triptych\" class=\"clearfix\">
      ";
            // line 177
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "triptych_first"), "html", null, true);
            echo "
      ";
            // line 178
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "triptych_middle"), "html", null, true);
            echo "
      ";
            // line 179
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "triptych_last"), "html", null, true);
            echo "
    </aside></div><!-- /#triptych, /#triptych-wrapper -->
  ";
        }
        // line 182
        echo "
  <div id=\"footer-wrapper\"><footer class=\"section\">

    ";
        // line 185
        if (((($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_firstcolumn") || $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_secondcolumn")) || $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_thirdcolumn")) || $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_fourthcolumn"))) {
            // line 186
            echo "      <div id=\"footer-columns\" class=\"clearfix\">
        ";
            // line 187
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_firstcolumn"), "html", null, true);
            echo "
        ";
            // line 188
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_secondcolumn"), "html", null, true);
            echo "
        ";
            // line 189
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_thirdcolumn"), "html", null, true);
            echo "
        ";
            // line 190
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer_fourthcolumn"), "html", null, true);
            echo "
      </div><!-- /#footer-columns -->
    ";
        }
        // line 193
        echo "
    ";
        // line 194
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer")) {
            // line 195
            echo "      <div id=\"footer\" role=\"contentinfo\" class=\"clearfix\">
        ";
            // line 196
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer"), "html", null, true);
            echo "
      </div> <!-- /#footer -->
   ";
        }
        // line 199
        echo "
  </footer></div> <!-- /.section, /#footer-wrapper -->

</div></div> <!-- /#page, /#page-wrapper -->
";
    }

    public function getTemplateName()
    {
        return "core/themes/bartik/templates/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  339 => 199,  333 => 196,  330 => 195,  328 => 194,  325 => 193,  319 => 190,  315 => 189,  311 => 188,  307 => 187,  304 => 186,  302 => 185,  297 => 182,  291 => 179,  287 => 178,  283 => 177,  280 => 176,  278 => 175,  273 => 172,  267 => 169,  264 => 168,  262 => 167,  259 => 166,  253 => 163,  250 => 162,  248 => 161,  242 => 158,  237 => 157,  231 => 154,  228 => 153,  226 => 152,  221 => 151,  215 => 148,  210 => 147,  208 => 146,  197 => 142,  194 => 141,  188 => 139,  185 => 138,  179 => 137,  173 => 134,  163 => 129,  160 => 128,  158 => 127,  144 => 121,  133 => 116,  120 => 110,  111 => 108,  108 => 107,  105 => 106,  95 => 103,  88 => 102,  75 => 97,  42 => 87,  33 => 85,  78 => 41,  69 => 37,  85 => 101,  77 => 37,  73 => 36,  68 => 35,  63 => 34,  53 => 91,  50 => 90,  44 => 27,  31 => 26,  203 => 145,  196 => 94,  192 => 140,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 132,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 115,  126 => 113,  107 => 65,  97 => 62,  92 => 46,  86 => 45,  83 => 57,  74 => 39,  62 => 93,  59 => 32,  56 => 48,  54 => 33,  32 => 28,  29 => 39,  60 => 52,  57 => 34,  51 => 48,  47 => 31,  35 => 86,  25 => 41,  23 => 81,  21 => 19,  155 => 126,  149 => 123,  146 => 122,  143 => 88,  137 => 117,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 109,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 38,  76 => 58,  70 => 51,  67 => 95,  64 => 94,  58 => 53,  55 => 92,  52 => 51,  46 => 48,  43 => 42,  41 => 46,  36 => 29,  30 => 43,  28 => 83,  24 => 26,  19 => 79,);
    }
}
