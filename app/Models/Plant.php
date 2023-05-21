<?php

namespace App\Models;

use App\Models\User;
use App\Models\Color;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
  use HasFactory;

  /**
   * createメソッドを使用時、許可する属性
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'file_path',
    'description',
  ];

  /**
   * 多対1 plants(子) 対 users(親)
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * 多対多 plants と colors
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function colors(): BelongsToMany
  {
    return $this->belongsToMany(Color::class)->withTimestamps();
  }

  /**
   * 多対多 plants と places
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function places(): BelongsToMany
  {
    return $this->belongsToMany(Place::class)->withTimestamps();
  }

  /**
   * 植物を登録する
   * @param \App\Models\User $user
   * @param \Illuminate\Http\Request $request
   * @param string $path
   * @return \App\Models\Plant
   */
  public function registerPlant(User $user, Request $request, string $path): Plant
  {
    $plant = $user->plants()->create([
      'name' => $request->name,
      'file_path' => $path,
      'description' => $request->description,
    ]);

    return $plant;
  }

  /**
   * ランダムに5つの植物を取得する
   * @return array
   */
  public function fetchRandomFivePlants(): array
  {
    return $this::all()->random(5)->all();
  }
}
