# 56th Module D — Backend RESTful API 專輯管理系統

> 來源: `docs/56th/ModuleD_RestfullAPI_v2.pdf`
> 競賽備註: 試題在競賽時得約有百分之三十之調整。

## 1. 系統介紹

專輯管理系統，由「管理員」管理所有發行者（user）的歌曲與專輯。
每首歌曲有唯一識別碼 ISRC（CC-XXX-YY-NNNNN），但 API 用 numeric `id`。

Base URL: `http://server_URL/webXX/module_d/api/...`

## 2. 認證 (這次的新要求)

| 項目 | 規則 |
|---|---|
| Header | `X-Authorization: Bearer <token>` |
| Token 產生方式 | `md5(username)` 全小寫 hex（32 字元） |
| Token 儲存 | 登入時寫入 `access_tokens` 表；登出時刪除/失效 |
| 401 missing | `{"success": false, "message": "Access Token is required"}` |
| 401 invalid | `{"success": false, "message": "Invalid Access Token"}` |
| 403 banned | `{"success": false, "message": "User is banned"}` |
| 403 admin-only | `{"success": false, "message": "Admin access required"}` |
| 403 not-owner | `{"success": false, "message": "Access denied"}` |

## 3. 資料模型

### users
| 欄位 | 型別 | 備註 |
|---|---|---|
| id | bigint PK | |
| username | string unique | |
| email | string unique | |
| password | string | bcrypt |
| role | enum('admin','user') | 預設 user |
| is_banned | bool | 預設 false |
| timestamps | | |

預設資料（seeder）:
- `admin / admin@web.wsa / adminpass / admin`
- `user1 / user1@web.wsa / user1pass / user`
- `user2 / user2@web.wsa / user2pass / user`

### access_tokens
| 欄位 | 型別 | 備註 |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK | |
| token | string(32) unique | md5(username) |
| created_at | timestamp | |

登出時刪除該 row，使 token 失效。

### albums (soft delete)
| 欄位 | 型別 |
|---|---|
| id | bigint PK |
| publisher_id | FK→users.id |
| title | string |
| artist | string |
| release_year | smallint |
| genre | string |
| description | text |
| timestamps + deleted_at | |

### songs (soft delete)
| 欄位 | 型別 |
|---|---|
| id | bigint PK |
| album_id | FK→albums.id |
| title | string |
| duration_seconds | int |
| lyrics | text |
| `order` | int |
| view_count | int default 0 |
| is_cover | bool default false |
| cover_image_path | string |
| timestamps + deleted_at | |

### labels (預設 8 個曲風)
`流行 / 搖滾 / 嘻哈 / 電子 / 爵士 / 經典 / 紓壓 / 鄉村`

### song_label (pivot)
| song_id | label_id |

## 4. 共通機制

### 4.1 Cursor-based 分頁
- 預設 `limit=10`、上限 `limit=100`
- `next_cursor` = `base64({"id": <last_id>})`
- 解析失敗 → 400 `Invalid cursor`
- `limit` > 100 或非數字 → 400 `Invalid parameter`

### 4.2 專輯封面組合 (`/api/albums/{id}/cover`)
- 來源：該 album 下 `is_cover = true` 的 songs，依 `order` asc
- count = 1 → 直接回傳該 song cover
- count = 2 → 左右並排
- count = 3 → 1+1 上、跨欄下，或左欄、右上+右下
- count > 3 → 400 `Too many covers provided`
- count = 0 → 404 `Cover Not Found`

### 4.3 Year filter (`/api/albums?year=...`)
- 單一年: `2020`
- 區間: `2018-2020`（小-大）
- 不合法（如 `abc`、`2000-1990`）→ 400 `Invalid year format`

### 4.4 軟刪除
- Album / Song 的 DELETE 為 soft delete

## 5. 22 支 API（端點清單）

### 公開 API（無需 token）

| # | Method | Path | 說明 |
|---|---|---|---|
| 1 | POST | `/api/login` | 登入，回傳 token |
| 2 | POST | `/api/register` | 註冊（自動 role=user） |
| 3 | GET | `/api/albums` | 列表，支援 `capital` `year` `limit` `cursor` |
| 4 | GET | `/api/albums/{id}` | 專輯詳情 |
| 5 | GET | `/api/albums/{id}/cover` | 動態組合封面（image/jpeg） |
| 6 | GET | `/api/albums/{id}/songs` | 該專輯歌曲，依 `order` asc |
| 7 | GET | `/api/songs` | 全歌曲，支援 `keyword` `limit` `cursor` |
| 8 | GET | `/api/songs/{id}/cover` | 歌曲封面（image/jpeg） |

### 使用者 API（需 token）

| # | Method | Path | 說明 |
|---|---|---|---|
| 9 | POST | `/api/logout` | 登出（失效 token） |
| 10 | GET | `/api/songs/{id}` | 詳情，**view_count++** |
| 11 | GET | `/api/statistics` | `metrics=song\|album\|label`，`labels=...` |

### 管理員 API（需 token + role=admin）

| # | Method | Path | 說明 |
|---|---|---|---|
| 12 | GET | `/api/users` | 全使用者 cursor 分頁 |
| 13 | PUT | `/api/users/{id}` | 更新 role |
| 14 | PUT | `/api/users/{id}/ban` | 封鎖（不可封鎖自己 / 其他 admin） |
| 15 | PUT | `/api/users/{id}/unban` | 解鎖 |
| 16 | POST | `/api/albums` | 創建（multipart） |
| 17 | PUT | `/api/albums/{id}` | 更新 title/description |
| 18 | DELETE | `/api/albums/{id}` | 軟刪除 |
| 19 | POST | `/api/albums/{id}/songs` | 新增歌曲（multipart, 含 cover_image） |
| 20 | PUT | `/api/albums/{id}/songs/order` | 重排順序 `{song_ids:[...]}` |
| 21 | POST | `/api/albums/{id}/songs/{song_id}` | 更新歌曲（multipart） |
| 22 | DELETE | `/api/albums/{id}/songs/{song_id}` | 軟刪除 |

註：管理員 API 操作 album/song 時，需檢查資源所有權（非自己的 → 403 `Access denied`）。

## 6. 錯誤訊息對照（必須完全相符 PDF）

| Code | Message |
|---|---|
| 400 | `Login failed`, `Validation failed`, `Too many covers provided`, `Invalid parameter`, `Invalid cursor`, `Invalid year format`, `Invalid file type`, `Cannot ban self` |
| 401 | `Access Token is required`, `Invalid Access Token` |
| 403 | `Access denied`, `Admin access required`, `User is banned`, `Last admin demotion forbidden`, `Cannot ban another admin` |
| 404 | `Not Found`, `Cover Not Found`, `User not found` |
| 409 | `Username already taken`, `Email already taken`, `Banned user update failed` |

統一格式：
```json
{ "success": false, "message": "..." }
```

## 7. 統計 API 行為（`/api/statistics`）

| metrics | 行為 |
|---|---|
| `song` | 列出所有歌曲，依 `view_count` desc。可選 `labels=Pop,Rock` 篩選 |
| `album` | 列出所有專輯，依 `total_view_count`(=∑song.view_count) desc |
| `label` | 依 label 分組，每組最多 10 首歌（依 view_count desc）。可 `labels=...` 篩選 |

## 8. 統一 Response 包裝

成功單筆: `{ "success": true, "data": {...} }`
成功列表: `{ "success": true, "data": [...], "meta": {"prev_cursor": "...", "next_cursor": "..."} }`
失敗: `{ "success": false, "message": "..." }`
