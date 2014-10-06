<?php

/* core/modules/system/templates/select.html.twig */
class __TwigTemplate_b61f5668a7ce5a9374d765202175e3a3060c548096f034579ee4326482e2fa96 extends Twig_Template
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
        // line 15
        echo "<select";
        echo twig_drupal_escape_filter($this->env, (isset($context["attributes"]) ? $context["attributes"] : null), "html", null, true);
        echo ">";
        echo twig_drupal_escape_filter($this->env, (isset($context["options"]) ? $context["options"] : null), "html", null, true);
        echo "</select>
";
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/select.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  63 => 39,  54 => 36,  52 => 35,  47 => 34,  39 => 31,  36 => 30,  21 => 17,  91 => 68,  85 => 65,  80 => 64,  77 => 63,  71 => 41,  68 => 60,  62 => 58,  60 => 38,  55 => 56,  49 => 53,  44 => 52,  41 => 32,  35 => 49,  32 => 48,  26 => 28,  24 => 26,  19 => 15,);
    }
}
