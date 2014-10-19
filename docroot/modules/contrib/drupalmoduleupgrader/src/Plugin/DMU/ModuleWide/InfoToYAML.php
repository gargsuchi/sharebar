<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\InfoToYAML.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\ConverterBase;
use Drupal\drupalmoduleupgrader\Issue;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @Converter(
 *  id = "InfoToYAML",
 *  description = @Translation("Converts Drupal 7 info files to Drupal 8."),
 *  documentation = {
 *    {
 *      "url" = "https://www.drupal.org/node/1935708",
 *      "title" = @Translation("<kbd>.info</kbd> files are now <kbd>.info.yml</kbd> files")
 *    }
 *  },
 *  message = @Translation("<kbd>.info</kbd> files are now <kbd>.info.yml</kbd> files.")
 * )
 */
class InfoToYAML extends ConverterBase {

  /**
   * {@inheritdoc}
   */
  public function isExecutable(TargetInterface $target) {
    $info_file = $target->getPath('.info.yml');
    return (! file_exists($info_file));
  }

  /**
   * {@inheritdoc}
   */
  public function analyze(TargetInterface $target) {
    $info_file = $target->getPath('.info');

    $info = self::parseInfo($info_file);
    if (empty($info)) {
      throw new \RuntimeException('Cannot parse info file ' . $info_file);
    }

    $issues = [];

    if ($info['core'] != '8.x') {
      $issues[] = new Issue($target, "Module info files' <var>core</var> key must have a value of <kbd>8.x</kbd>.");
    }
    if (empty($info['type'])) {
      $issues['type'] = new Issue($target, 'Info files must contain a <var>type</var> key.');
    }
    if (isset($info['dependencies'])) {
      $issues[] = new Issue($target, 'Many common dependencies have moved into core.');
    }
    if (isset($info['files'])) {
      $issues['files'] = new Issue($target, 'Modules no longer declare classes in their info file.');
    }
    if (isset($info['configure'])) {
      $issues['configure'] = new Issue($target, "Module info files' <var>configure</var> key must be a route name, not a path.");
    }

    /** @var \Drupal\drupalmoduleupgrader\IssueInterface $issue */
    foreach ($issues as $key => $issue) {
      $url = 'https://www.drupal.org/node/1935708';
      if (is_string($key)) {
        $url .= '#' . $key;
      }
      $issue->addAffectedFile($info_file, $this);
      $issue->addDocumentation($url, '<kbd>.info</kbd> files are now <kbd>.info.yml</kbd> files');
      $target->getReport()->addIssue($issue);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    $info_file = $target->getPath('.info');

    $info = self::parseInfo($info_file);
    $info['core'] = '8.x';
    $info['type'] = 'module';

    if (isset($info['dependencies'])) {
      // array_values() is called in order to reindex the array. Issue #2340207
      $info['dependencies'] = array_values(array_diff($info['dependencies'], ['ctools', 'list']));
    }

    unset($info['files'], $info['configure']);
    $this->writeInfo($target, 'info', $info);
  }

  /**
   * Parses a D7 info file. This is copied straight outta the D7 function 
   * drupal_parse_info_format().
   */
  public static function parseInfo($file) {
    $info = [];
    $constants = get_defined_constants();
    $data = file_get_contents($file);

    if (preg_match_all('
      @^\s*                           # Start at the beginning of a line, ignoring leading whitespace
      ((?:
        [^=;\[\]]|                    # Key names cannot contain equal signs, semi-colons or square brackets,
        \[[^\[\]]*\]                  # unless they are balanced and not nested
      )+?)
      \s*=\s*                         # Key/value pairs are separated by equal signs (ignoring white-space)
      (?:
        ("(?:[^"]|(?<=\\\\)")*")|     # Double-quoted string, which may contain slash-escaped quotes/slashes
        (\'(?:[^\']|(?<=\\\\)\')*\')| # Single-quoted string, which may contain slash-escaped quotes/slashes
        ([^\r\n]*?)                   # Non-quoted string
      )\s*$                           # Stop at the next end of a line, ignoring trailing whitespace
      @msx', $data, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $match) {
        // Fetch the key and value string.
        $i = 0;
        foreach (array('key', 'value1', 'value2', 'value3') as $var) {
          $$var = isset($match[++$i]) ? $match[$i] : '';
        }
        $value = stripslashes(substr($value1, 1, -1)) . stripslashes(substr($value2, 1, -1)) . $value3;

        // Parse array syntax.
        $keys = preg_split('/\]?\[/', rtrim($key, ']'));
        $last = array_pop($keys);
        $parent = &$info;

        // Create nested arrays.
        foreach ($keys as $key) {
          if ($key == '') {
            $key = count($parent);
          }
          if (!isset($parent[$key]) || !is_array($parent[$key])) {
            $parent[$key] = array();
          }
          $parent = &$parent[$key];
        }

        // Handle PHP constants.
        if (isset($constants[$value])) {
          $value = $constants[$value];
        }

        // Insert actual value.
        if ($last == '') {
          $last = count($parent);
        }
        $parent[$last] = $value;
      }
    }

    return $info;
  }

}
