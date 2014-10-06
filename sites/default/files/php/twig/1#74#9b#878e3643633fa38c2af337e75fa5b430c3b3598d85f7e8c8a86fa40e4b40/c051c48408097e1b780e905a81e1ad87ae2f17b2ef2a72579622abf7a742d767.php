<?php

/* @system/block--system-menu-block.html.twig */
class __TwigTemplate_749b878e3643633fa38c2af337e75fa5b430c3b3598d85f7e8c8a86fa40e4b40 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 44
        $context["classes"] = array(0 => "block", 1 => "block-menu", 2 => "navigation", 3 => ("menu--" . drupal_html_class((isset($context["derivative_plugin_id"]) ? $context["derivative_plugin_id"] : null))));
        // line 51
        $context["heading_id"] = ($this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "id") . drupal_clean_id_identifier("-menu"));
        // line 52
        echo "<nav";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null)), "method"), "html", null, true);
        echo " role=\"navigation\" aria-labelledby=\"";
        echo twig_drupal_escape_filter($this->env, (isset($context["heading_id"]) ? $context["heading_id"] : null), "html", null, true);
        echo "\">
  ";
        // line 54
        echo "  ";
        if ((!$this->getAttribute((isset($context["configuration"]) ? $context["configuration"] : null), "label_display"))) {
            // line 55
            echo "    ";
            $context["title_attributes"] = $this->getAttribute((isset($context["title_attributes"]) ? $context["title_attributes"] : null), "addClass", array(0 => "visually-hidden"), "method");
            // line 56
            echo "  ";
        }
        // line 57
        echo "  ";
        echo twig_drupal_escape_filter($this->env, (isset($context["title_prefix"]) ? $context["title_prefix"] : null), "html", null, true);
        echo "
  <h2 id=\"";
        // line 58
        echo twig_drupal_escape_filter($this->env, (isset($context["heading_id"]) ? $context["heading_id"] : null), "html", null, true);
        echo "\"";
        echo twig_drupal_escape_filter($this->env, (isset($context["title_attributes"]) ? $context["title_attributes"] : null), "html", null, true);
        echo ">";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["configuration"]) ? $context["configuration"] : null), "label"), "html", null, true);
        echo "</h2>
  ";
        // line 59
        echo twig_drupal_escape_filter($this->env, (isset($context["title_suffix"]) ? $context["title_suffix"] : null), "html", null, true);
        echo "

  ";
        // line 62
        echo "  ";
        $this->displayBlock('content', $context, $blocks);
        // line 65
        echo "</nav>
";
    }

    // line 62
    public function block_content($context, array $blocks = array())
    {
        // line 63
        echo "    ";
        echo twig_drupal_escape_filter($this->env, (isset($context["content"]) ? $context["content"] : null), "html", null, true);
        echo "
  ";
    }

    public function getTemplateName()
    {
        return "@system/block--system-menu-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 62,  61 => 65,  45 => 58,  34 => 55,  22 => 51,  20 => 44,  48 => 18,  40 => 57,  37 => 56,  27 => 11,  339 => 199,  333 => 196,  330 => 195,  328 => 194,  325 => 193,  319 => 190,  315 => 189,  311 => 188,  307 => 187,  304 => 186,  302 => 185,  297 => 182,  291 => 179,  287 => 178,  283 => 177,  280 => 176,  278 => 175,  273 => 172,  267 => 169,  264 => 168,  262 => 167,  259 => 166,  253 => 163,  250 => 162,  248 => 161,  242 => 158,  237 => 157,  231 => 154,  228 => 153,  226 => 152,  221 => 151,  215 => 148,  210 => 147,  208 => 146,  197 => 142,  194 => 141,  188 => 139,  185 => 138,  179 => 137,  173 => 134,  163 => 129,  160 => 128,  158 => 127,  144 => 121,  133 => 116,  120 => 110,  111 => 108,  108 => 107,  105 => 106,  95 => 103,  88 => 102,  75 => 24,  42 => 87,  33 => 85,  78 => 41,  69 => 63,  85 => 101,  77 => 25,  73 => 36,  68 => 35,  63 => 21,  53 => 59,  50 => 90,  44 => 27,  31 => 54,  203 => 145,  196 => 94,  192 => 140,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 132,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 115,  126 => 113,  107 => 65,  97 => 62,  92 => 46,  86 => 45,  83 => 57,  74 => 39,  62 => 93,  59 => 32,  56 => 48,  54 => 33,  32 => 12,  29 => 39,  60 => 52,  57 => 20,  51 => 48,  47 => 31,  35 => 13,  25 => 10,  23 => 81,  21 => 19,  155 => 126,  149 => 123,  146 => 122,  143 => 88,  137 => 117,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 109,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 38,  76 => 58,  70 => 23,  67 => 95,  64 => 94,  58 => 62,  55 => 92,  52 => 51,  46 => 17,  43 => 42,  41 => 46,  36 => 29,  30 => 43,  28 => 83,  24 => 52,  19 => 79,);
    }
}
