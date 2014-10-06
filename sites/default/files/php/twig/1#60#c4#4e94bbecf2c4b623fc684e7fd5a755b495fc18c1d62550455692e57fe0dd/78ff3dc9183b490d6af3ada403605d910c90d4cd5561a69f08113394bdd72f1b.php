<?php

/* core/modules/system/templates/progress-bar.html.twig */
class __TwigTemplate_60c44e94bbecf2c4b623fc684e7fd5a755b495fc18c1d62550455692e57fe0dd extends Twig_Template
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
        // line 16
        echo "<div class=\"progress\" data-drupal-progress>
  ";
        // line 17
        if ((isset($context["label"]) ? $context["label"] : null)) {
            // line 18
            echo "    <div class=\"progress__label\">";
            echo twig_drupal_escape_filter($this->env, (isset($context["label"]) ? $context["label"] : null), "html", null, true);
            echo "</div>
  ";
        }
        // line 20
        echo "  <div class=\"progress__track\"><div class=\"progress__bar\" style=\"width: ";
        echo twig_drupal_escape_filter($this->env, (isset($context["percent"]) ? $context["percent"] : null), "html", null, true);
        echo "%\"></div></div>
  <div class=\"progress__percentage\">";
        // line 21
        echo twig_drupal_escape_filter($this->env, (isset($context["percent"]) ? $context["percent"] : null), "html", null, true);
        echo "%</div>
  <div class=\"progress__description\">";
        // line 22
        echo twig_drupal_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : null), "html", null, true);
        echo "</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/progress-bar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 22,  35 => 21,  30 => 20,  22 => 17,  85 => 52,  81 => 50,  78 => 49,  72 => 47,  68 => 45,  59 => 43,  55 => 42,  43 => 38,  40 => 37,  34 => 34,  25 => 28,  28 => 25,  21 => 23,  49 => 40,  42 => 23,  27 => 33,  23 => 27,  97 => 45,  91 => 42,  88 => 41,  86 => 40,  83 => 39,  80 => 38,  76 => 36,  73 => 35,  71 => 34,  65 => 31,  60 => 30,  54 => 28,  52 => 41,  48 => 25,  45 => 24,  41 => 22,  38 => 21,  36 => 35,  32 => 21,  26 => 16,  24 => 18,  19 => 16,);
    }
}
