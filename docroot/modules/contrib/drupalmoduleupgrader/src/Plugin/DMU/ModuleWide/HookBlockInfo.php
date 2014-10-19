<?php

/**
 * @file
 * Contains Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide\HookBlockInfo.
 */

namespace Drupal\drupalmoduleupgrader\Plugin\DMU\ModuleWide;

use Drupal\drupalmoduleupgrader\Converter\CleanerInterface;
use Drupal\drupalmoduleupgrader\Converter\HookConverterBase;
use Drupal\drupalmoduleupgrader\TargetInterface;
use Drupal\drupalmoduleupgrader\Utility\StringTransformTrait;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Converter(
 *  id = "HookBlockInfo",
 *  description = @Translation("Converts Drupal 7's hook_block_info() to plugins."),
 *  documentation = {
 *    {
 *      "url" = "https://api.drupal.org/api/drupal/core%21modules%21block%21block.api.php/group/block_api/8",
 *      "title" = @Translation("Drupal 8 Block API documentation")
 *    }
 *  },
 *  message = @Translation("Blocks are now plugins in the <code>MODULE\Plugin\Block</code> namespace."),
 *  hook = "block_info"
 * )
 */
class HookBlockInfo extends HookConverterBase implements CleanerInterface {

  use StringTransformTrait;

  /**
   * {@inheritdoc}
   */
  public function convert(TargetInterface $target) {
    /** @var \Pharborist\Functions\FunctionDeclarationNode $hook */
    $hook = $target->getIndexer()->get('hook', 'block_info')->get(0);
    eval($hook->getText());
    $blocks = call_user_func($hook->getName()->getText());

    $fs = new Filesystem();

    foreach ($blocks as $id => $info) {
      // Render the block plugin's shell.
      $render = [
        '#theme' => 'dmu_block',
        '#module' => $target->id(),
        '#class' => $this->toTitleCase($id),
        '#block_id' => $id,
        '#block_label' => $info['info'],
        // @todo Check for 'hook', 'block_configure' when this plugin explicitly
        // implements IndexerInterface and can therefore index all block hooks.
        '#configurable' => $target->getIndexer()->has('function', $target->id() . '_block_configure'),
      ];

      $destination = $target->getPath('src/Plugin/Block/' . $render['#class'] . '.php');
      $fs->dumpFile($destination, "<?php\n\n" . render($render));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function clean(TargetInterface $target) {
    $target->getIndexer()->get('hook', 'block_info')->get(0)->remove();
    $doc = $target->getCodeManager()->open($target->getPath('.module'));
    $target->getCodeManager()->save($doc);
  }

}
