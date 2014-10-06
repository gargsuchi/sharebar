<?php

/* core/modules/system/templates/status-messages.html.twig */
class __TwigTemplate_e354ad6295f008e2af0da78ff7365ee630bf3d9f0546fb9f65c21b35ae0a6020 extends Twig_Template
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
        // line 26
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["message_list"]) ? $context["message_list"] : null));
        foreach ($context['_seq'] as $context["type"] => $context["messages"]) {
            // line 27
            echo "  ";
            // line 28
            $context["classes"] = array(0 => "messages", 1 => ("messages--" . (isset($context["type"]) ? $context["type"] : null)));
            // line 33
            echo "  <div class=\"";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null)), "method"), "class"), "html", null, true);
            echo "\" role=\"contentinfo\" aria-label=\"";
            echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["status_headings"]) ? $context["status_headings"] : null), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "html", null, true);
            echo "\">
    ";
            // line 34
            if (((isset($context["type"]) ? $context["type"] : null) == "error")) {
                // line 35
                echo "      <div role=\"alert\">
    ";
            }
            // line 37
            echo "      ";
            if ($this->getAttribute((isset($context["status_headings"]) ? $context["status_headings"] : null), (isset($context["type"]) ? $context["type"] : null), array(), "array")) {
                // line 38
                echo "        <h2 class=\"visually-hidden\">";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["status_headings"]) ? $context["status_headings"] : null), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "html", null, true);
                echo "</h2>
      ";
            }
            // line 40
            echo "      ";
            if ((twig_length_filter($this->env, (isset($context["messages"]) ? $context["messages"] : null)) > 1)) {
                // line 41
                echo "        <ul class=\"messages__list\">
          ";
                // line 42
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["messages"]) ? $context["messages"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                    // line 43
                    echo "            <li class=\"messages__item\">";
                    echo twig_drupal_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : null), "html", null, true);
                    echo "</li>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 45
                echo "        </ul>
      ";
            } else {
                // line 47
                echo "        ";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["messages"]) ? $context["messages"] : null), 0), "html", null, true);
                echo "
      ";
            }
            // line 49
            echo "    ";
            if (((isset($context["type"]) ? $context["type"] : null) == "error")) {
                // line 50
                echo "      </div>
    ";
            }
            // line 52
            echo "  </div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['type'], $context['messages'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/status-messages.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 52,  81 => 50,  78 => 49,  72 => 47,  68 => 45,  59 => 43,  55 => 42,  43 => 38,  40 => 37,  34 => 34,  25 => 28,  28 => 25,  21 => 23,  49 => 40,  42 => 23,  27 => 33,  23 => 27,  97 => 45,  91 => 42,  88 => 41,  86 => 40,  83 => 39,  80 => 38,  76 => 36,  73 => 35,  71 => 34,  65 => 31,  60 => 30,  54 => 28,  52 => 41,  48 => 25,  45 => 24,  41 => 22,  38 => 21,  36 => 35,  32 => 21,  26 => 16,  24 => 15,  19 => 26,);
    }
}
