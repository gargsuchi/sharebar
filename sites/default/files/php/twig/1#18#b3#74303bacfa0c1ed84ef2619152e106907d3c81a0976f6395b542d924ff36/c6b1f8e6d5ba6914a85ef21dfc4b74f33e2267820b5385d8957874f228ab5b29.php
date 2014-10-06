<?php

/* core/themes/bartik/templates/block--system-menu-block.html.twig */
class __TwigTemplate_18b374303bacfa0c1ed84ef2619152e106907d3c81a0976f6395b542d924ff36 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("@system/block--system-menu-block.html.twig");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@system/block--system-menu-block.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 10
        $context["show_anchor"] = ("show-" . drupal_clean_id_identifier($this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "id")));
        // line 11
        $context["hide_anchor"] = ("hide-" . drupal_clean_id_identifier($this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "id")));
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "  ";
        // line 14
        echo "  ";
        if ($this->getAttribute((isset($context["configuration"]) ? $context["configuration"] : null), "label_display")) {
            // line 15
            echo "    <div";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["content_attributes"]) ? $context["content_attributes"] : null), "addClass", array(0 => "content"), "method"), "html", null, true);
            echo ">
  ";
        } else {
            // line 17
            echo "    ";
            // line 18
            echo "    <div class=\"menu-toggle-target menu-toggle-target-show\" id=\"";
            echo twig_drupal_escape_filter($this->env, (isset($context["show_anchor"]) ? $context["show_anchor"] : null), "html", null, true);
            echo "\"></div>
    <div class=\"menu-toggle-target\" id=\"";
            // line 19
            echo twig_drupal_escape_filter($this->env, (isset($context["hide_anchor"]) ? $context["hide_anchor"] : null), "html", null, true);
            echo "\"></div>
    <a class=\"menu-toggle\" href=\"#";
            // line 20
            echo twig_drupal_escape_filter($this->env, (isset($context["show_anchor"]) ? $context["show_anchor"] : null), "html", null, true);
            echo "\">";
            echo twig_render_var(t("Menu"));
            echo "</a>
    <a class=\"menu-toggle menu-toggle--hide\" href=\"#";
            // line 21
            echo twig_drupal_escape_filter($this->env, (isset($context["hide_anchor"]) ? $context["hide_anchor"] : null), "html", null, true);
            echo "\">";
            echo twig_render_var(t("Menu"));
            echo "</a>
  ";
        }
        // line 23
        echo "  ";
        echo twig_drupal_escape_filter($this->env, (isset($context["content"]) ? $context["content"] : null), "html", null, true);
        echo "
  ";
        // line 24
        if ($this->getAttribute((isset($context["configuration"]) ? $context["configuration"] : null), "label_display")) {
            // line 25
            echo "    </div>
  ";
        }
    }

    public function getTemplateName()
    {
        return "core/themes/bartik/templates/block--system-menu-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 18,  40 => 15,  37 => 14,  27 => 11,  339 => 199,  333 => 196,  330 => 195,  328 => 194,  325 => 193,  319 => 190,  315 => 189,  311 => 188,  307 => 187,  304 => 186,  302 => 185,  297 => 182,  291 => 179,  287 => 178,  283 => 177,  280 => 176,  278 => 175,  273 => 172,  267 => 169,  264 => 168,  262 => 167,  259 => 166,  253 => 163,  250 => 162,  248 => 161,  242 => 158,  237 => 157,  231 => 154,  228 => 153,  226 => 152,  221 => 151,  215 => 148,  210 => 147,  208 => 146,  197 => 142,  194 => 141,  188 => 139,  185 => 138,  179 => 137,  173 => 134,  163 => 129,  160 => 128,  158 => 127,  144 => 121,  133 => 116,  120 => 110,  111 => 108,  108 => 107,  105 => 106,  95 => 103,  88 => 102,  75 => 24,  42 => 87,  33 => 85,  78 => 41,  69 => 37,  85 => 101,  77 => 25,  73 => 36,  68 => 35,  63 => 21,  53 => 19,  50 => 90,  44 => 27,  31 => 26,  203 => 145,  196 => 94,  192 => 140,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 132,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 115,  126 => 113,  107 => 65,  97 => 62,  92 => 46,  86 => 45,  83 => 57,  74 => 39,  62 => 93,  59 => 32,  56 => 48,  54 => 33,  32 => 12,  29 => 39,  60 => 52,  57 => 20,  51 => 48,  47 => 31,  35 => 13,  25 => 10,  23 => 81,  21 => 19,  155 => 126,  149 => 123,  146 => 122,  143 => 88,  137 => 117,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 109,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 38,  76 => 58,  70 => 23,  67 => 95,  64 => 94,  58 => 53,  55 => 92,  52 => 51,  46 => 17,  43 => 42,  41 => 46,  36 => 29,  30 => 43,  28 => 83,  24 => 26,  19 => 79,);
    }
}
