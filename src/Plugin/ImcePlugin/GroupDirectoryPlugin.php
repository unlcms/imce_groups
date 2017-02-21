<?php

namespace Drupal\imce_groups\Plugin\ImcePlugin;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\file\Entity\FileInterface;
use Drupal\imce\ImcePluginBase;

/**
 * Defines Imce Upload plugin.
 *
 * @ImcePlugin(
 *   id = "group_directories",
 *   label = "Group Directories",
 *   weight = -10
 * )
 */
class GroupDirectoryPlugin extends ImcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function processUserConf(array &$conf, AccountProxyInterface $user) {
    $group_membership_service = \Drupal::service('group.membership_loader');
    $group_memberships = $group_membership_service->loadByUser($user);
    foreach ($group_memberships as $group_membership) {
      /**
       * @var \Drupal\group\Entity\Group $group
       */
      $group = $group_membership->getGroup();
      $subdomain = \Drupal::service('path.alias_manager')->getAliasByPath('/group/' . $group->id());
      $conf['folders']['groups/'.trim($subdomain, '/')] = [
        'permissions' => [
          //assume all permissions should be granted
          //TODO: how to allow customizing the permissions for the group?
          'all' => true,
        ]
      ];
    }
  }
}
