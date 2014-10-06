<?php

/* core/themes/bartik/templates/block.html.twig */
class __TwigTemplate_c0eca26f34a9a1bb7e915acc3f9ae3ffc970e853525c6dbdfee28e79fab7f3f4 extends Twig_Template
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
        // line 37
        $context["classes"] = array(0 => "block", 1 => ("block-" . drupal_html_class($this->getAttribute((isset($context["configuration"]) ? $context["configuration"] : null), "provider"))));
        // line 42
        echo "<div";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null)), "method"), "html", null, true);
        echo ">
  ";
        // line 43
        echo twig_drupal_escape_filter($this->env, (isset($context["title_prefix"]) ? $context["title_prefix"] : null), "html", null, true);
        echo "
  ";
        // line 44
        if ((isset($context["label"]) ? $context["label"] : null)) {
            // line 45
            echo "    <h2";
            echo twig_drupal_escape_filter($this->env, (isset($context["title_attributes"]) ? $context["title_attributes"] : null), "html", null, true);
            echo ">";
            echo twig_drupal_escape_filter($this->env, (isset($context["label"]) ? $context["label"] : null), "html", null, true);
            echo "</h2>
  ";
        }
        // line 47
        echo "  ";
        echo twig_drupal_escape_filter($this->env, (isset($context["title_suffix"]) ? $context["title_suffix"] : null), "html", null, true);
        echo "
  ";
        // line 48
        $this->displayBlock('content', $context, $blocks);
        // line 53
        echo "</div>
";
    }

    // line 48
    public function block_content($context, array $blocks = array())
    {
        // line 49
        echo "    <div";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["content_attributes"]) ? $context["content_attributes"] : null), "addClass", array(0 => "content"), "method"), "html", null, true);
        echo ">
      ";
        // line 50
        echo twig_drupal_escape_filter($this->env, (isset($context["content"]) ? $context["content"] : null), "html", null, true);
        echo "
    </div>
  ";
    }

    public function getTemplateName()
    {
        return "core/themes/bartik/templates/block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 19,  66 => 62,  61 => 50,  45 => 58,  34 => 55,  22 => 42,  20 => 37,  48 => 53,  40 => 57,  37 => 56,  27 => 43,  339 => 199,  333 => 196,  330 => 195,  328 => 194,  325 => 193,  319 => 190,  315 => 189,  311 => 188,  307 => 187,  304 => 186,  302 => 185,  297 => 182,  291 => 179,  287 => 178,  283 => 177,  280 => 176,  278 => 175,  273 => 172,  267 => 169,  264 => 168,  262 => 167,  259 => 166,  253 => 163,  250 => 162,  248 => 161,  242 => 158,  237 => 157,  231 => 154,  228 => 153,  226 => 152,  221 => 151,  215 => 148,  210 => 147,  208 => 146,  197 => 142,  194 => 141,  188 => 139,  185 => 138,  179 => 137,  173 => 134,  163 => 129,  160 => 128,  158 => 127,  144 => 121,  133 => 116,  120 => 110,  111 => 108,  108 => 107,  105 => 106,  95 => 103,  88 => 102,  75 => 24,  42 => 87,  33 => 45,  78 => 41,  69 => 63,  85 => 101,  77 => 25,  73 => 36,  68 => 35,  63 => 21,  53 => 48,  50 => 90,  44 => 27,  31 => 44,  203 => 145,  196 => 94,  192 => 140,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 132,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 115,  126 => 113,  107 => 65,  97 => 62,  92 => 46,  86 => 45,  83 => 57,  74 => 39,  62 => 93,  59 => 25,  56 => 49,  54 => 33,  32 => 12,  29 => 16,  60 => 52,  57 => 20,  51 => 48,  47 => 31,  35 => 13,  25 => 10,  23 => 81,  21 => 13,  155 => 126,  149 => 123,  146 => 122,  143 => 88,  137 => 117,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 109,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 38,  76 => 58,  70 => 23,  67 => 95,  64 => 94,  58 => 62,  55 => 92,  52 => 23,  46 => 48,  43 => 42,  41 => 47,  36 => 18,  30 => 43,  28 => 83,  24 => 14,  19 => 12,);
    }
}
