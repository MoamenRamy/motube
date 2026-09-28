# MoTube

A full-stack video-sharing platform built with **Laravel 10**, allowing users to create channels, upload videos, interact with content, manage watch history, and receive notifications.

The project was built as a practical Laravel application to demonstrate authentication, video processing, background jobs, user interactions, administration, real-time notifications, and responsive web development.

---

## 🚀 Features

### 👤 User Authentication

- User registration
- User login/logout
- Email verification
- Password reset
- Password confirmation
- Profile management
- Profile photo support
- Two-factor authentication
- Browser session management
- API authentication with Laravel Sanctum

### 📺 Channels

Users can have their own channels and manage their uploaded content.

Features include:

- View all channels
- Search channels
- View channel videos
- Channel management
- Channel administration
- Block/unblock channels
- Update channel permissions
- Delete channels

### 🎬 Video Management

Authenticated users can upload and manage videos.

Features include:

- Upload videos
- Upload custom thumbnails
- Automatic thumbnail resizing
- Edit video title
- Replace video thumbnails
- Delete videos
- View personal uploaded videos
- Search videos
- View video details
- Track video views
- Video processing status
- Video duration detection
- Video quality detection
- Landscape and portrait video support

### 🎞️ Video Processing

The application uses **FFmpeg** to process uploaded videos in the background.

Uploaded videos are automatically converted into multiple resolutions and formats.

Supported resolutions:

- 1080p
- 720p
- 480p
- 360p
- 240p

Supported formats:

- MP4
- WebM

Video processing is handled through Laravel Queue Jobs so that heavy video conversion does not block the main web request.

### 👍 Likes & Dislikes

Authenticated users can interact with videos using:

- Like
- Dislike
- Remove reaction
- Change reaction

The application dynamically calculates:

- Total likes
- Total dislikes

### 💬 Comments

Authenticated users can:

- Add comments
- Edit comments
- Delete comments
- View comments on videos

Comments are submitted through AJAX and returned as JSON responses.

### 🕒 Watch History

Authenticated users automatically get videos added to their watch history.

Users can:

- View watch history
- Remove individual videos
- Clear all watch history

### 🔔 Notifications

The application provides a notification system for users.

Notifications are generated when video processing is completed or fails.

Features include:

- Recent notifications
- All notifications
- Notification alerts
- Real-time notification events
- Successful processing notifications
- Failed processing notifications

### 👨‍💼 Admin Dashboard

The application includes an administration area for managing the platform.

Admin features include:

- Dashboard statistics
- Total videos
- Total channels
- Most active channels
- Most viewed videos
- Channel management
- Update channel permissions
- Delete channels
- Block channels
- Unblock channels
- View blocked channels
- View all channels

### 📊 Statistics

The admin dashboard provides statistics including:

- Total number of videos
- Total number of channels
- Most viewed videos
- Most active channels
- Video view counts

Charts are implemented using **Chart.js**.

---

# 🧠 Main Application Flow

The application follows a video-processing workflow:

```text
User uploads video
        │
        ▼
Video + Thumbnail stored
        │
        ▼
Video record created
        │
        ▼
Laravel Queue Job dispatched
        │
        ▼
FFmpeg analyzes video
        │
        ▼
Video resolution detected
        │
        ▼
Multiple video qualities generated
        │
        ├── 1080p
        ├── 720p
        ├── 480p
        ├── 360p
        └── 240p
        │
        ▼
MP4 + WebM versions generated
        │
        ▼
Original video removed
        │
        ▼
Processed video information stored
        │
        ▼
User notification generated
        │
        ▼
Video becomes available for streaming
```

---

# 🎥 Video Processing

One of the main features of MoTube is asynchronous video processing.

The application uses:

- Laravel Jobs
- Laravel Queue
- FFmpeg
- X264
- WebM
- FFProbe

The main processing job is:

```text
app/Jobs/ConvertVideoForStreaming.php
```

The job implements:

```php
ShouldQueue
```

This allows video conversion to run as a background task.

---

## 🎞️ Supported Video Qualities

| Quality | Resolution |
|---|---|
| 1080p | 1920 × 1080 |
| 720p | 1280 × 720 |
| 480p | 854 × 480 |
| 360p | 640 × 360 |
| 240p | 426 × 240 |

The application checks the original video's dimensions and determines the highest supported quality.

It also supports portrait/vertical videos by detecting whether the height is greater than the width.

---

## 📦 Video Formats

Each supported quality can be generated in:

| Format | Video Codec | Audio Codec |
|---|---|---|
| MP4 | H.264 / libx264 | AAC |
| WebM | VP8 / libvpx | Vorbis |

---

# 🖼️ Thumbnail Processing

Uploaded thumbnails are processed using:

```text
Intervention Image
```

The application automatically resizes thumbnails to:

```text
320 × 180
```

This provides a consistent thumbnail size across the platform.

---

# 🔐 Authentication

Authentication is implemented using:

```text
Laravel Jetstream
Laravel Fortify
Laravel Sanctum
```

The application includes:

- Registration
- Login
- Logout
- Email verification
- Password reset
- Password confirmation
- Two-factor authentication
- Session management
- Profile management

Protected application routes use Laravel authentication middleware.

---

# 🔑 API Authentication

The project also includes Laravel Sanctum authentication.

The default API endpoint:

```http
GET /api/user
```

is protected by:

```php
auth:sanctum
```

Authenticated users can retrieve their current user information through the API.

---

# 🌐 Routes

## Main Routes

| Method | Endpoint | Description |
|---|---|---|
| GET | `/` | Main page |
| GET | `/main/{channel}/videos` | View channel videos |
| GET | `/videos` | User videos |
| GET | `/videos/create` | Upload video page |
| GET | `/videos/{video}` | View video |
| GET | `/videos/{video}/edit` | Edit video |
| POST | `/videos` | Upload video |
| PUT/PATCH | `/videos/{video}` | Update video |
| DELETE | `/videos/{video}` | Delete video |
| GET | `/video/search` | Search videos |

---

# 👍 Video Interactions

## Like / Dislike

```http
POST /like
```

The request contains the video ID and reaction type.

Example:

```json
{
    "videoId": 1,
    "isLike": "true"
}
```

The response returns:

```json
{
    "countLike": 10,
    "countDislike": 2
}
```

---

# 👁️ Video Views

Views are registered through:

```http
POST /view
```

Example request:

```json
{
    "videoId": 1
}
```

Example response:

```json
{
    "viewsNumbers": 125
}
```

---

# 💬 Comments API

## Add Comment

```http
POST /comment
```

Example request:

```json
{
    "videoId": 1,
    "comment": "Great video!"
}
```

Example response:

```json
{
    "userName": "Moamen",
    "userImage": "...",
    "commentDate": "2 minutes ago",
    "commentId": 15
}
```

## Edit Comment

```http
GET /comment/{id}/edit
```

## Update Comment

```http
PATCH /comment/{id}
```

## Delete Comment

```http
GET /comment/{id}
```

---

# 🕒 Watch History

## View History

```http
GET /history
```

## Delete Video From History

```http
DELETE /history/{id}
```

## Clear History

```http
DELETE /distroyAll
```

---

# 📺 Channels

## All Channels

```http
GET /channel
```

## Search Channels

```http
GET /channel/search
```

## Channel Videos

```http
GET /main/{channel}/videos
```

---

# 🔔 Notifications

## Recent Notifications

```http
POST /Notifications
```

Returns the latest notifications for the authenticated user.

## All Notifications

```http
GET /Notifications
```

Displays all notifications belonging to the authenticated user.

---

# 👨‍💼 Admin Panel

Administrative routes are protected using Laravel Gate/Policy authorization.

The main admin prefix is:

```text
/admin
```

The admin area requires the appropriate:

```text
can:update-videos
```

permission.

Additional operations use:

```text
can:update-users
```

---

## Admin Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/admin` | Admin dashboard |
| GET | `/admin/channels` | Manage channels |
| PATCH | `/admin/{channel}/channels` | Update channel permissions |
| DELETE | `/admin/channels/{channel}` | Delete channel |
| PATCH | `/admin/{channel}/block` | Block channel |
| GET | `/admin/channels/blocked` | View blocked channels |
| PATCH | `/admin/{channel}/unblock` | Unblock channel |
| GET | `/admin/allChannels` | View all channels |
| GET | `/admin/MostViewedVideos` | View most viewed videos |

---

# 📊 Admin Dashboard

The administration dashboard provides platform statistics.

It displays:

```text
Total Videos
Total Channels
Most Active Channels
Most Viewed Videos
```

The project uses database aggregation to calculate total views per user/channel.

Example concept:

```sql
SUM(views.views_number)
GROUP BY user_id
ORDER BY total DESC
```

---

# 🔎 Search

The application provides search functionality for:

### Videos

```http
GET /video/search?term=laravel
```

### Channels

```http
GET /channel/search?term=moamen
```

Search results are paginated.

---

# 🏗️ Project Architecture

The application follows Laravel's MVC architecture.

```text
MoTube/
│
├── app/
│   ├── Actions/
│   │   ├── Fortify/
│   │   └── Jetstream/
│   │
│   ├── Console/
│   │
│   ├── Events/
│   │   ├── FailedNotification.php
│   │   └── RealNotification.php
│   │
│   ├── Exceptions/
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminsController.php
│   │   │   ├── ChannelController.php
│   │   │   ├── CommentController.php
│   │   │   ├── HistoryController.php
│   │   │   ├── LikeController.php
│   │   │   ├── MainController.php
│   │   │   ├── NotificationController.php
│   │   │   └── VideoController.php
│   │   │
│   │   └── Middleware/
│   │
│   ├── Jobs/
│   │   └── ConvertVideoForStreaming.php
│   │
│   ├── Models/
│   │   ├── Alert.php
│   │   ├── Comment.php
│   │   ├── Convertedvideo.php
│   │   ├── Like.php
│   │   ├── Membership.php
│   │   ├── Notification.php
│   │   ├── Team.php
│   │   ├── TeamInvitation.php
│   │   ├── User.php
│   │   ├── Video.php
│   │   └── View.php
│   │
│   ├── Policies/
│   │   └── TeamPolicy.php
│   │
│   └── Providers/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
│
├── storage/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

# 🛠️ Tech Stack

## Backend

| Technology | Purpose |
|---|---|
| PHP 8.1+ | Backend programming |
| Laravel 10 | Web application framework |
| Laravel Jetstream | Authentication & application scaffolding |
| Laravel Fortify | Authentication backend |
| Laravel Sanctum | API authentication |
| Laravel Eloquent | ORM |
| Laravel Queues | Background processing |
| Laravel Events | Application events |
| Laravel Notifications | User notifications |

## Video Processing

| Technology | Purpose |
|---|---|
| FFmpeg | Video conversion |
| FFProbe | Video metadata analysis |
| X264 | H.264 video encoding |
| WebM | Web video format |
| ProtoneMedia Laravel FFmpeg | Laravel FFmpeg integration |

## Frontend

| Technology | Purpose |
|---|---|
| Blade | Server-side templating |
| Livewire | Reactive Laravel components |
| Tailwind CSS | UI styling |
| Alpine.js | Frontend interactions |
| Axios | HTTP requests |
| Chart.js | Admin statistics and charts |
| Vite | Frontend asset bundling |

## Storage

| Technology | Purpose |
|---|---|
| Laravel Filesystem | File management |
| Flysystem | Storage abstraction |
| AWS S3 Adapter | S3-compatible storage support |

---

# 📦 Composer Dependencies

The project uses several important Laravel packages.

```json
{
    "intervention/image": "^2.7",
    "laravel/framework": "^10.10",
    "laravel/jetstream": "^3.3",
    "laravel/sanctum": "^3.2",
    "livewire/livewire": "^2.11",
    "league/flysystem-aws-s3-v3": "^3.0",
    "pbmedia/laravel-ffmpeg": "^8.3",
    "pusher/pusher-php-server": "^7.2"
}
```

---

# 🎨 Frontend Dependencies

The frontend is built using Laravel Vite and includes:

- Tailwind CSS
- Alpine.js
- Axios
- Chart.js
- Laravel Vite Plugin
- PostCSS
- Autoprefixer

---

# ⚙️ Installation

## 1. Clone the Repository

```bash
git clone https://github.com/MoamenRamy/motube.git
```

Move into the project:

```bash
cd motube
```

---

## 2. Install PHP Dependencies

```bash
composer install
```

---

## 3. Install Frontend Dependencies

```bash
npm install
```

---

## 4. Create Environment File

### Windows

```bash
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Configure Database

Open:

```text
.env
```

Configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=motube
DB_USERNAME=root
DB_PASSWORD=
```

---

## 7. Run Migrations

```bash
php artisan migrate
```

If seeders are available and required:

```bash
php artisan migrate --seed
```

---

## 8. Create Storage Link

```bash
php artisan storage:link
```

---

# 🎬 FFmpeg Configuration

Because MoTube processes uploaded videos, **FFmpeg must be installed and available on the server/environment**.

Verify FFmpeg:

```bash
ffmpeg -version
```

Verify FFProbe:

```bash
ffprobe -version
```

The application uses FFmpeg to:

- Analyze uploaded videos
- Detect resolution
- Detect duration
- Convert videos
- Generate multiple qualities
- Generate MP4 versions
- Generate WebM versions

---

# ⚡ Queue Worker

Video conversion runs through Laravel Queue Jobs.

After configuring the queue, run:

```bash
php artisan queue:work
```

The main job responsible for video processing is:

```text
app/Jobs/ConvertVideoForStreaming.php
```

Without a running queue worker, uploaded videos that require background processing will not finish conversion.

---

# 🔥 Development Server

Start Laravel:

```bash
php artisan serve
```

Start Vite:

```bash
npm run dev
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

---

# 🏭 Production Build

Build frontend assets:

```bash
npm run build
```

For production, make sure the following are configured correctly:

- Database
- Storage
- FFmpeg
- FFProbe
- Queue worker
- Environment variables
- Application key
- Web server
- File permissions

---

# 🧪 Testing

The project includes Laravel Feature and Unit tests.

Run the complete test suite:

```bash
php artisan test
```

Or:

```bash
./vendor/bin/phpunit
```

---

# 🧪 Existing Feature Tests

The project includes tests covering authentication and application functionality, including:

- Authentication
- Registration
- Email verification
- Password reset
- Password updates
- Password confirmation
- Profile information
- API token creation
- API token deletion
- Browser sessions
- Two-factor authentication
- Account deletion
- Team creation
- Team deletion
- Team member invitations
- Team member removal
- Team member role updates
- Team name updates

---

# 🔄 Application Architecture

The application follows a traditional Laravel MVC structure:

```text
Browser
   │
   ▼
Routes
   │
   ▼
Controllers
   │
   ├── Models ───────► Database
   │
   ├── Views ────────► Blade
   │
   ├── Jobs ─────────► Queue
   │
   ├── Events ───────► Notifications
   │
   └── Storage ──────► Video Files
```

---

# 🧠 Laravel Concepts Demonstrated

This project demonstrates practical usage of:

- Laravel MVC
- Laravel Routing
- Resource Controllers
- Middleware
- Authentication
- Authorization
- Laravel Jetstream
- Laravel Fortify
- Laravel Sanctum
- Eloquent ORM
- Eloquent Relationships
- Model Binding
- Validation
- File Uploads
- Laravel Storage
- Image Processing
- Laravel Queues
- Background Jobs
- Laravel Events
- Notifications
- AJAX Requests
- JSON Responses
- Pagination
- Database Queries
- Query Builder
- Database Aggregation
- Route Groups
- Route Prefixes
- Gates / Permissions
- Blade Templates
- Livewire
- Vite
- Tailwind CSS
- Alpine.js
- Chart.js
- FFmpeg Integration

---

# 💡 Key Technical Highlights

### Background Video Processing

Instead of converting uploaded videos during the HTTP request, the application dispatches:

```php
Bus::dispatch(new ConvertVideoForStreaming($video));
```

This moves the heavy processing workload into a queue.

---

### Automatic Video Quality Detection

The application uses FFProbe to inspect:

```text
Video Width
Video Height
Video Duration
```

It then selects the appropriate source quality and generates the supported streaming formats.

---

### Multiple Streaming Qualities

A single uploaded video can be converted into multiple resolutions:

```text
1080p
720p
480p
360p
240p
```

This provides flexibility for different network speeds and device capabilities.

---

### User Interaction System

Videos support:

```text
Views
Likes
Dislikes
Comments
History
Notifications
```

This creates a complete video-platform interaction workflow.

---

# 📈 Admin Analytics

The admin dashboard uses database aggregation to calculate channel activity and video statistics.

The project uses:

```php
DB::raw('sum(views.views_number)')
```

to calculate total views.

Chart.js is then used to visualize the statistics in the dashboard.

---

# 🔔 Event-Based Notifications

The application defines events for both successful and failed video processing:

```text
RealNotification
FailedNotification
```

Successful processing:

```text
Video processed
      ↓
Notification created
      ↓
RealNotification event
      ↓
User alert updated
```

Failed processing:

```text
Video processing failed
      ↓
Failed notification created
      ↓
FailedNotification event
      ↓
User alert updated
```

---

# 🗃️ Main Models

The application contains models for the main platform entities:

```text
User
Video
Convertedvideo
Comment
Like
View
Notification
Alert
Membership
Team
TeamInvitation
```

---

# 🔗 Main Relationships

The application uses Eloquent relationships to connect users and video-related data.

Conceptually:

```text
User
 │
 ├── Videos
 │    │
 │    ├── Views
 │    ├── Likes
 │    ├── Comments
 │    └── Converted Videos
 │
 ├── Notifications
 │
 └── Watch History
```

---

# 📌 Project Goals

MoTube was developed as a practical full-stack Laravel project focused on building a complete video-sharing platform.

The project demonstrates how different Laravel components can work together in a real-world application:

```text
Authentication
      +
Video Upload
      +
File Storage
      +
FFmpeg Processing
      +
Queue Jobs
      +
Database Relationships
      +
User Interactions
      +
Notifications
      +
Admin Dashboard
      =
Complete Video Platform
```

---

# 👨‍💻 Author

**Moamen Ramy**

PHP / Laravel Backend Developer

- GitHub: [MoamenRamy](https://github.com/MoamenRamy)
- LinkedIn: [Moamen Ramy](https://www.linkedin.com/in/moamen-ramy-492a8b212/)

---

# 📂 Repository

[View MoTube on GitHub](https://github.com/MoamenRamy/motube)

---

# ⭐ Project Summary

MoTube is a Laravel-based video-sharing platform featuring user authentication, channels, video uploads, video processing with FFmpeg, multiple streaming qualities, likes, dislikes, comments, watch history, notifications, and an administrative dashboard.

The project demonstrates practical backend and full-stack development using Laravel and its ecosystem, with an emphasis on real-world application architecture and asynchronous video processing.
