<?php

/* core/modules/toolbar/templates/toolbar.html.twig */
class __TwigTemplate_5646a3b4d808ee4d329a7a090a0b01de527a9eea6f07bb977dae752f573c76e8 extends Twig_Template
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
        // line 25
        echo "<div";
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => "toolbar"), "method"), "html", null, true);
        echo ">
  <nav";
        // line 26
        echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["toolbar_attributes"]) ? $context["toolbar_attributes"] : null), "addClass", array(0 => "toolbar-bar", 1 => "clearfix"), "method"), "html", null, true);
        echo ">
    <h2 class=\"visually-hidden\">";
        // line 27
        echo twig_drupal_escape_filter($this->env, (isset($context["toolbar_heading"]) ? $context["toolbar_heading"] : null), "html", null, true);
        echo "</h2>
    ";
        // line 28
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["tabs"]) ? $context["tabs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["tab"]) {
            // line 29
            echo "      <div";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["tab"]) ? $context["tab"] : null), "attributes"), "addClass", array(0 => "toolbar-tab"), "method"), "html", null, true);
            echo ">";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["tab"]) ? $context["tab"] : null), "link"), "html", null, true);
            echo "</div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tab'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "  </nav>
  ";
        // line 32
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["trays"]) ? $context["trays"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["tray"]) {
            // line 33
            echo "    ";
            ob_start();
            // line 34
            echo "    <div";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["tray"]) ? $context["tray"] : null), "attributes"), "html", null, true);
            echo ">
    ";
            // line 35
            if ($this->getAttribute((isset($context["tray"]) ? $context["tray"] : null), "label")) {
                // line 36
                echo "      <nav class=\"toolbar-lining clearfix\" role=\"navigation\" aria-label=\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["tray"]) ? $context["tray"] : null), "label"), "html", null, true);
                echo "\">
          <h3 class=\"toolbar-tray-name visually-hidden\">";
                // line 37
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["tray"]) ? $context["tray"] : null), "label"), "html", null, true);
                echo "</h3>
    ";
            } else {
                // line 39
                echo "      <nav class=\"toolbar-lining clearfix\" role=\"navigation\">
    ";
            }
            // line 41
            echo "        ";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["tray"]) ? $context["tray"] : null), "links"), "html", null, true);
            echo "
      </nav>
    </div>
    ";
            echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
            // line 45
            echo "  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tray'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "  ";
        echo twig_drupal_escape_filter($this->env, (isset($context["remainder"]) ? $context["remainder"] : null), "html", null, true);
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "core/modules/toolbar/templates/toolbar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 41,  69 => 37,  85 => 40,  77 => 37,  73 => 36,  68 => 35,  63 => 34,  53 => 30,  50 => 32,  44 => 27,  31 => 26,  203 => 98,  196 => 94,  192 => 93,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 85,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 72,  126 => 70,  107 => 65,  97 => 62,  92 => 46,  86 => 45,  83 => 57,  74 => 39,  62 => 35,  59 => 32,  56 => 48,  54 => 33,  32 => 28,  29 => 39,  60 => 52,  57 => 34,  51 => 48,  47 => 31,  35 => 41,  25 => 41,  23 => 40,  21 => 19,  155 => 93,  149 => 80,  146 => 89,  143 => 88,  137 => 85,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 67,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 38,  76 => 58,  70 => 51,  67 => 54,  64 => 36,  58 => 53,  55 => 49,  52 => 51,  46 => 48,  43 => 42,  41 => 46,  36 => 29,  30 => 43,  28 => 27,  24 => 26,  19 => 25,);
    }
}
