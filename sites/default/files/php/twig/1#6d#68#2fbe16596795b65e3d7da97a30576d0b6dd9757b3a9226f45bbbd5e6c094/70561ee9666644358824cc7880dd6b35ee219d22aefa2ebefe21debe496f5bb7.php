<?php

/* core/modules/system/templates/pager.html.twig */
class __TwigTemplate_6d682fbe16596795b65e3d7da97a30576d0b6dd9757b3a9226f45bbbd5e6c094 extends Twig_Template
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
        // line 34
        if ((isset($context["items"]) ? $context["items"] : null)) {
            // line 35
            echo "  <nav class=\"pager\" role=\"navigation\" aria-labelledby=\"pagination-heading\">
    <h4 id=\"pagination-heading\" class=\"visually-hidden\">";
            // line 36
            echo twig_render_var(t("Pagination"));
            echo "</h4>
    <ul class=\"pager__items\">
      ";
            // line 39
            echo "      ";
            if ($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "first")) {
                // line 40
                echo "        <li class=\"pager__item pager__item--first\">
          <a href=\"";
                // line 41
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "first"), "href"), "html", null, true);
                echo "\" title=\"";
                echo twig_render_var(t("Go to first page"));
                echo "\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "first"), "attributes"), "html", null, true);
                echo ">
            <span class=\"visually-hidden\">";
                // line 42
                echo twig_render_var(t("First page"));
                echo "</span>
            <span aria-hidden=\"true\">";
                // line 43
                echo twig_drupal_escape_filter($this->env, (($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "first", array(), "any", false, true), "text", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "first", array(), "any", false, true), "text"), t("« first"))) : (t("« first"))), "html", null, true);
                echo "</span>
          </a>
        </li>
      ";
            }
            // line 47
            echo "      ";
            // line 48
            echo "      ";
            if ($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "previous")) {
                // line 49
                echo "        <li class=\"pager__item pager__item--previous\">
          <a href=\"";
                // line 50
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "previous"), "href"), "html", null, true);
                echo "\" title=\"";
                echo twig_render_var(t("Go to previous page"));
                echo "\" rel=\"prev\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "previous"), "attributes"), "html", null, true);
                echo ">
            <span class=\"visually-hidden\">";
                // line 51
                echo twig_render_var(t("Previous page"));
                echo "</span>
            <span aria-hidden=\"true\">";
                // line 52
                echo twig_drupal_escape_filter($this->env, (($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "previous", array(), "any", false, true), "text", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "previous", array(), "any", false, true), "text"), t("‹ previous"))) : (t("‹ previous"))), "html", null, true);
                echo "</span>
          </a>
        </li>
      ";
            }
            // line 56
            echo "      ";
            // line 57
            echo "      ";
            if ($this->getAttribute((isset($context["ellipses"]) ? $context["ellipses"] : null), "previous")) {
                // line 58
                echo "        <li class=\"pager__item pager__item--ellipsis\" role=\"presentation\">&hellip;</li>
      ";
            }
            // line 60
            echo "      ";
            // line 61
            echo "      ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "pages"));
            foreach ($context['_seq'] as $context["key"] => $context["item"]) {
                // line 62
                echo "        <li class=\"pager__item";
                echo twig_render_var(((((isset($context["current"]) ? $context["current"] : null) == (isset($context["key"]) ? $context["key"] : null))) ? (" is-active") : ("")));
                echo "\">
          ";
                // line 63
                if (((isset($context["current"]) ? $context["current"] : null) == (isset($context["key"]) ? $context["key"] : null))) {
                    // line 64
                    echo "            ";
                    $context["title"] = t("Current page");
                    // line 65
                    echo "          ";
                } else {
                    // line 66
                    echo "            ";
                    $context["title"] = t("Go to page @key", array("@key" => (isset($context["key"]) ? $context["key"] : null)));
                    // line 67
                    echo "          ";
                }
                // line 68
                echo "          <a href=\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : null), "href"), "html", null, true);
                echo "\" title=\"";
                echo twig_drupal_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
                echo "\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : null), "attributes"), "html", null, true);
                echo ">
            <span class=\"visually-hidden\">
              ";
                // line 70
                echo twig_render_var(((((isset($context["current"]) ? $context["current"] : null) == (isset($context["key"]) ? $context["key"] : null))) ? (t("Current page")) : (t("Page"))));
                echo "
            </span>";
                // line 72
                echo twig_drupal_escape_filter($this->env, (isset($context["key"]) ? $context["key"] : null), "html", null, true);
                // line 73
                echo "</a>
        </li>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 76
            echo "      ";
            // line 77
            echo "      ";
            if ($this->getAttribute((isset($context["ellipses"]) ? $context["ellipses"] : null), "next")) {
                // line 78
                echo "        <li class=\"pager__item pager__item--ellipsis\" role=\"presentation\">&hellip;</li>
      ";
            }
            // line 80
            echo "      ";
            // line 81
            echo "      ";
            if ($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "next")) {
                // line 82
                echo "        <li class=\"pager__item pager__item--next\">
          <a href=\"";
                // line 83
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "next"), "href"), "html", null, true);
                echo "\" title=\"";
                echo twig_render_var(t("Go to next page"));
                echo "\" rel=\"next\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "next"), "attributes"), "html", null, true);
                echo ">
            <span class=\"visually-hidden\">";
                // line 84
                echo twig_render_var(t("Next page"));
                echo "</span>
            <span aria-hidden=\"true\">";
                // line 85
                echo twig_drupal_escape_filter($this->env, (($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "next", array(), "any", false, true), "text", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "next", array(), "any", false, true), "text"), t("next ›"))) : (t("next ›"))), "html", null, true);
                echo "</span>
          </a>
        </li>
      ";
            }
            // line 89
            echo "      ";
            // line 90
            echo "      ";
            if ($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "last")) {
                // line 91
                echo "        <li class=\"pager__item pager__item--last\">
          <a href=\"";
                // line 92
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "last"), "href"), "html", null, true);
                echo "\" title=\"";
                echo twig_render_var(t("Go to last page"));
                echo "\"";
                echo twig_drupal_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "last"), "attributes"), "html", null, true);
                echo ">
            <span class=\"visually-hidden\">";
                // line 93
                echo twig_render_var(t("Last page"));
                echo "</span>
            <span aria-hidden=\"true\">";
                // line 94
                echo twig_drupal_escape_filter($this->env, (($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "last", array(), "any", false, true), "text", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["items"]) ? $context["items"] : null), "last", array(), "any", false, true), "text"), t("last »"))) : (t("last »"))), "html", null, true);
                echo "</span>
          </a>
        </li>
      ";
            }
            // line 98
            echo "    </ul>
  </nav>
";
        }
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/pager.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  203 => 98,  196 => 94,  192 => 93,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 85,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 72,  126 => 70,  107 => 65,  97 => 62,  92 => 61,  86 => 58,  83 => 57,  74 => 52,  62 => 50,  59 => 49,  56 => 48,  54 => 47,  32 => 40,  29 => 39,  60 => 52,  57 => 50,  51 => 48,  47 => 43,  35 => 41,  25 => 41,  23 => 40,  21 => 35,  155 => 93,  149 => 80,  146 => 89,  143 => 88,  137 => 85,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 67,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 62,  76 => 58,  70 => 51,  67 => 54,  64 => 56,  58 => 53,  55 => 49,  52 => 51,  46 => 48,  43 => 42,  41 => 46,  36 => 45,  30 => 43,  28 => 42,  24 => 36,  19 => 34,);
    }
}
