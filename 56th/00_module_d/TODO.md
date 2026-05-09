# 56th Module D — 實作 Todo

> 本 todo 與 `SPEC.md` 對應。每項標記 *worktree-safe* 表示模組獨立，可平行實作。

## 已完成（端點骨架）

- [x] 資料庫遷移：users 擴充欄位、albums、songs、labels、song_label、access_tokens
- [x] Eloquent 模型：User / Album / Song / Label / AccessToken
- [x] Middleware：`AccessTokenAuth`（X-Authorization Bearer）+ `AdminOnly`
- [x] Routes：22 條路由全部註冊（routes/api.php）
- [x] Controllers：方法骨架就緒（AuthController 完整實作；其餘為 stub）

## 待實作（按優先序）

### Phase 1 — 公開 API（基礎）
- [ ] `POST /api/login`（worktree-safe；AuthController）
  - 驗證 username/password；建立或更新 access_tokens row（token = md5(username)）
- [ ] `POST /api/register`（worktree-safe；AuthController）
  - 唯一性檢查、回傳 user 物件
- [ ] `GET /api/albums`（AlbumController.index）
  - cursor 分頁、capital/year filter、回傳 publisher 巢狀
- [ ] `GET /api/albums/{id}`（AlbumController.show）
- [ ] `GET /api/albums/{id}/songs`（AlbumController.songs；依 order asc）
- [ ] `GET /api/songs`（SongController.index；keyword filter + cursor）

### Phase 2 — 圖片串流（worktree-safe，可獨立）
- [ ] `GET /api/songs/{id}/cover`（直接讀檔回傳 jpeg）
- [ ] `GET /api/albums/{id}/cover`
  - 依 is_cover songs 動態合成 1 / 2 / 3 張的拼貼圖
  - 用 `intervention/image` 套件（需 `composer require intervention/image`）
  - >3 cover → 400；0 cover → 404

### Phase 3 — 使用者 API（需 token）
- [ ] `POST /api/logout`（AuthController.logout；刪 token row）
- [ ] `GET /api/songs/{id}`（SongController.show；view_count++）
- [ ] `GET /api/statistics`（StatisticsController；3 種 metrics）

### Phase 4 — 管理員：使用者管理（worktree-safe）
- [ ] `GET /api/users`（UserController.index；cursor）
- [ ] `PUT /api/users/{id}`（更新 role；最後一位 admin 不可降級；banned user 不可更新）
- [ ] `PUT /api/users/{id}/ban`（不可封鎖自己；不可封鎖另一位 admin）
- [ ] `PUT /api/users/{id}/unban`

### Phase 5 — 管理員：專輯/歌曲管理
- [ ] `POST /api/albums`（multipart；publisher_id = current user）
- [ ] `PUT /api/albums/{id}`（只能改自己的；title + description）
- [ ] `DELETE /api/albums/{id}`（soft delete；ownership check）
- [ ] `POST /api/albums/{id}/songs`（multipart；含 cover_image upload）
  - label 要驗證屬於 8 個預設曲風
  - 新增 is_cover=true 時若會超過 3 張 → 400
- [ ] `PUT /api/albums/{id}/songs/order`（song_ids 全部需屬於該 album）
- [ ] `POST /api/albums/{id}/songs/{song_id}`（更新；同樣 is_cover 上限檢查）
- [ ] `DELETE /api/albums/{id}/songs/{song_id}`（soft delete）

### Phase 6 — 全域處理
- [ ] Exception handler：`NotFoundHttpException` → JSON 404 `Not Found`
- [ ] Validation exception → JSON 400 `Validation failed`
- [ ] Route fallback → JSON 404
- [ ] Seeder：3 個預設使用者 + 8 個曲風 label

### 測試 / 驗收
- [ ] Postman collection 涵蓋 22 條 + 各錯誤碼
- [ ] 跑 `php artisan route:list` 比對 SPEC §5
- [ ] 各端點以 curl 實打驗證 happy path + 401/403/404 路徑
