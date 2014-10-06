<?php

/* core/modules/system/templates/menu.html.twig */
class __TwigTemplate_4b2e2caa3c5588910d109e8f3c5126f16c189109eb06f1e0556939096c585f53 extends Twig_Template
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
        // line 18
        $context["menus"] = $this;
        // line 19
        echo "
";
        // line 24
        echo twig_render_var($context["menus"]->getmenu_links((isset($context["items"]) ? $context["items"] : null), (isset($context["attributes"]) ? $context["attributes"] : null), 0));
        echo "

";
    }

    // line 26
    public function getmenu_links($_items = null, $_attributes = null, $_menu_level = null)
    {
        $context = $this->env->mergeGlobals(array(
            "items" => $_items,
            "attributes" => $_attributes,
            "menu_level" => $_menu_level,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 27
            echo "  ";
            $context["menus"] = $this;
            // line 28
            echo "  ";
            if ((isset($context["items"]) ? $context["items"] : null)) {
                // line 29
                echo "    ";
                if (((isset($context["menu_level"]) ? $context["menu_level"] : null) == 0)) {
                    // line 30
                    echo "      <ul";
                    echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => "menu"), "method"), "html", null, true);
                    echo ">
    ";
                } else {
                    // line 32
                    echo "      <ul class=\"menu\">
    ";
                }
                // line 34
                echo "      ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["items"]) ? $context["items"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 35
                    echo "        <li";
                    echo twig_drupal_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : null), "attributes"), "html", null, true);
                    echo ">
          ";
                    // line 36
                    echo twig_drupal_escape_filter($this->env, $this->env->getExtension('drupal_core')->getLink($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "title"), $this->getAttribute((isset($context["item"]) ? $context["item"] : null), "url")), "html", null, true);
                    echo "
          ";
                    // line 37
                    if ($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "below")) {
                        // line 38
                        echo "            ";
                        echo twig_render_var($context["menus"]->getmenu_links($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "below"), (isset($context["attributes"]) ? $context["attributes"] : null), ((isset($context["menu_level"]) ? $context["menu_level"] : null) + 1)));
                        echo "
          ";
                    }
                    // line 40
                    echo "        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 42
                echo "    </ul>
  ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/menu.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 40,  77 => 37,  73 => 36,  68 => 35,  63 => 34,  53 => 30,  50 => 29,  44 => 27,  31 => 26,  203 => 98,  196 => 94,  192 => 93,  184 => 92,  181 => 91,  178 => 90,  176 => 89,  169 => 85,  165 => 84,  157 => 83,  154 => 82,  151 => 81,  145 => 78,  142 => 77,  140 => 76,  132 => 73,  130 => 72,  126 => 70,  107 => 65,  97 => 62,  92 => 42,  86 => 58,  83 => 57,  74 => 52,  62 => 50,  59 => 32,  56 => 48,  54 => 47,  32 => 40,  29 => 39,  60 => 52,  57 => 50,  51 => 48,  47 => 28,  35 => 41,  25 => 41,  23 => 40,  21 => 19,  155 => 93,  149 => 80,  146 => 89,  143 => 88,  137 => 85,  134 => 84,  131 => 83,  125 => 81,  122 => 80,  116 => 68,  113 => 67,  110 => 66,  104 => 64,  102 => 63,  99 => 71,  93 => 68,  90 => 60,  84 => 64,  81 => 56,  79 => 38,  76 => 58,  70 => 51,  67 => 54,  64 => 56,  58 => 53,  55 => 49,  52 => 51,  46 => 48,  43 => 42,  41 => 46,  36 => 45,  30 => 43,  28 => 42,  24 => 24,  19 => 18,);
    }
}
