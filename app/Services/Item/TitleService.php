<?php

namespace App\Services\Item;

use App\Models\Character\CharacterTitle;
use App\Services\Service;

use DB;

use App\Services\InventoryManager;

class TitleService extends Service {
  /*
    |--------------------------------------------------------------------------
    | Title Service
    |--------------------------------------------------------------------------
    |
    | Handles the editing and usage of title type items.
    |
    */

  /**
   * Retrieves any data that should be used in the item tag editing form.
   *
   * @return array
   */
  public function getEditData() {
    return [
      'titles' => CharacterTitle::orderBy('title')->where('is_user_selectable', 0)->pluck('title', 'id'),
    ];
  }

  /**
   * Processes the data attribute of the tag and returns it in the preferred format.
   *
   * @param  object  $tag
   * @return mixed
   */
  public function getTagData($tag) {
    if (isset($tag->data['all_titles'])) return 'All';
    $rewards = [];
    if ($tag->data) {
      $assets = parseAssetData($tag->data);
      foreach ($assets as $type => $a) {
        $class = getAssetModelString($type, false);
        foreach ($a as $id => $asset) {
          $rewards[] = (object)[
            'rewardable_type' => $class,
            'rewardable_id' => $id,
            'quantity' => 1
          ];
        }
      }
    }
    return $rewards;
  }

  /**
   * Processes the data attribute of the tag and returns it in the preferred format.
   *
   * @param  object  $tag
   * @param  array   $data
   * @return bool
   */
  public function updateData($tag, $data) {
    DB::beginTransaction();

    try {
      // If there's no data, save no data.
      if (!isset($data['rewardable_id']) && !isset($data['all_titles'])){
        $tag->update(['data' => null]);
        return $this->commitReturn(true);
      }
      //if there is data save
      if (isset($data['all_titles'])) $assets = ['all_titles' => 1];
      else {
        // The data will be stored as an asset table, json_encode()d.
        // First build the asset table, then prepare it for storage.
        $assets = createAssetsArray();
        foreach ($data['rewardable_id'] as $key => $r) {
          $asset = CharacterTitle::find($data['rewardable_id'][$key]);
          addAsset($assets, $asset, 1);
        }
        $assets = getDataReadyAssets($assets);
      }
      $tag->update(['data' => json_encode($assets)]);

      return $this->commitReturn(true);
    } catch (\Exception $e) {
      $this->setError('error', $e->getMessage());
    }
    return $this->rollbackReturn(false);
  }


  /**
   * Acts upon the item when used from the inventory.
   *
   * @param  \App\Models\User\UserItem  $stacks
   * @param  \App\Models\User\User      $user
   * @param  array                      $data
   * @return bool
   */
  public function act($stacks, $user, $data) {
    DB::beginTransaction();

    try {
      $firstData = $stacks->first()->item->tag('title')->data;
      if (isset($firstData['all_titles']) && $firstData['all_titles']) {
        $titleOptions = CharacterTitle::where('is_user_selectable', 0)->whereNotIn('id', $user->titles->pluck('id')->toArray())->get();
      } elseif (isset($firstData['titles']) && count($firstData['titles'])) {
        $titleOptions = CharacterTitle::find(array_keys($firstData['titles']))->where('is_user_selectable', 0)->whereNotIn('id', $user->titles->pluck('id')->toArray());
      }

      $options = $titleOptions->pluck('id')->toArray();
      if (!count($options)) throw new \Exception("There are no more options for this title unlock item.");
      if (count($options) < array_sum($data['quantities'])) throw new \Exception("You have selected a quantity too high for the quantity of titles you can unlock with this item.");

      foreach ($stacks as $key => $stack) {

        // We don't want to let anyone who isn't the owner of the box open it,
        // so do some validation...
        if ($stack->user_id != $user->id) throw new \Exception("This item does not belong to you.");

        // Next, try to delete the box item. If successful, we can start distributing rewards.
        if ((new InventoryManager)->debitStack($stack->user, 'Title Redeemed', ['data' => ''], $stack, $data['quantities'][$key])) {
          for ($q = 0; $q < $data['quantities'][$key]; $q++) {

            $random = array_rand($options);
            $thisTitle['titles'] = [$options[$random] => 1];
            unset($options[$random]);

            // Distribute user rewards
            if (!$rewards = fillUserAssets(parseAssetData($thisTitle), $user, $user, 'Title Redemption', [
              'data' => 'Redeemed from ' . $stack->item->name
            ])) throw new \Exception("Failed to open title redemption item.");
            flash($this->getTitleRewardsString($rewards));
          }
        }
      }
      return $this->commitReturn(true);
    } catch (\Exception $e) {
      $this->setError('error', $e->getMessage());
    }
    return $this->rollbackReturn(false);
  }

  /**
   * Acts upon the item when used from the inventory.
   *
   * @param  array                  $rewards
   * @return string
   */
  private function getTitleRewardsString($rewards) {
    $results = "You have unlocked the following title: ";
    $result_elements = [];
    foreach ($rewards as $assetType) {
      if (isset($assetType)) {
        foreach ($assetType as $asset) {
          array_push($result_elements, $asset['asset']->displayName);
        }
      }
    }
    return $results . implode(', ', $result_elements);
  }
}
