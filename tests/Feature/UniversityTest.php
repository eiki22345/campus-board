<?php

use App\Models\Region;
use App\Models\University;

test('大学一覧ページが表示される', function () {
  $region = Region::factory()->create(['name' => '北海道']);
  University::factory()->create(['name' => '北海道大学', 'region_id' => $region->id]);

  $response = $this->get(route('universities.index'));

  $response->assertOk();
  $response->assertSee('北海道大学');
});

test('未ログインでも大学一覧ページにアクセスできる', function () {
  $response = $this->get(route('universities.index'));

  $response->assertOk();
});

test('キーワードで大学名を検索できる', function () {
  $region = Region::factory()->create();
  University::factory()->create(['name' => '北海道大学', 'region_id' => $region->id]);
  University::factory()->create(['name' => '東京大学', 'region_id' => $region->id]);

  $response = $this->get(route('universities.index', ['keyword' => '北海道']));

  $response->assertOk();
  $response->assertSee('北海道大学');
  $response->assertDontSee('東京大学');
});

test('キーワードでメールドメインを検索できる', function () {
  $region = Region::factory()->create();
  University::factory()->create(['name' => '北海道大学', 'email_domain' => 'hokudai.ac.jp', 'region_id' => $region->id]);
  University::factory()->create(['name' => '東京大学', 'email_domain' => 'u-tokyo.ac.jp', 'region_id' => $region->id]);

  $response = $this->get(route('universities.index', ['keyword' => 'hokudai']));

  $response->assertOk();
  $response->assertSee('北海道大学');
  $response->assertDontSee('東京大学');
});

test('検索結果が0件でも正常に表示される', function () {
  $response = $this->get(route('universities.index', ['keyword' => '存在しない大学XYZ']));

  $response->assertOk();
});
