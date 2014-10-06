<?php

/* core/modules/system/templates/task-list.html.twig */
class __TwigTemplate_ca7d1cd029819d1ba64891a2bd4ed682acafeb9da8cb37e57579435b28084b5b extends Twig_Template
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
        // line 17
        echo "<h2 class=\"visually-hidden\">Installation tasks</h2>
<ol class=\"task-list\">
";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["tasks"]) ? $context["tasks"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["task"]) {
            // line 20
            echo "  <li";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["task"]) ? $context["task"] : null), "attributes"), "html", null, true);
            echo ">
    ";
            // line 21
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["task"]) ? $context["task"] : null), "item"), "html", null, true);
            echo "
    ";
            // line 22
            if ($this->getAttribute((isset($context["task"]) ? $context["task"] : null), "status")) {
                echo "<span class=\"visually-hidden\"> (";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["task"]) ? $context["task"] : null), "status"), "html", null, true);
                echo ")</span>";
            }
            // line 23
            echo "  </li>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['task'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "</ol>
";
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/task-list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 25,  42 => 23,  27 => 20,  23 => 19,  97 => 45,  91 => 42,  88 => 41,  86 => 40,  83 => 39,  80 => 38,  76 => 36,  73 => 35,  71 => 34,  65 => 31,  60 => 30,  54 => 28,  52 => 27,  48 => 25,  45 => 24,  41 => 22,  38 => 21,  36 => 22,  32 => 21,  26 => 16,  24 => 15,  19 => 17,);
    }
}
