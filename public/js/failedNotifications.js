var notificationsWrapper = $('.alert-dropdown');
var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
var notificationsCountElem = notificationsToggle.find('span[data-count]');
var notificationsCount = parseInt(notificationsCountElem.data('count'));
var notifications = notificationsWrapper.find('div.alert-body');

// Subscribe to the channel we specified in our Laravel Event
var channel = pusher.subscribe('failed-notification');
// Bind a function to a Event (the full Laravel class)
channel.bind('App\\Events\\FailedNotification', function (data) {
    var existingNotifications = notifications.html();
    var newNotificationHtml = '<a class="dropdown-item d-flex align-items-center" href="#">\
                                    <div class="mr-3">\
                                        <div class="icon-circle bg-secondary">\
                                            <i class="far fa-bell text-white"></i>\
                                        </div>\
                                    </div>\
                                    <div>\
                                        <div class="small text-gray-500">'+data.date+'</div>\
                                        <span>Unfortunately, an unexpected error occurred while processing the video <b>'+data.video_title+'</b> Please upload it again</span>\
                                    </div>\
                                </a>';
    notifications.html(newNotificationHtml + existingNotifications);
    notificationsCount += 1;
    notificationsCountElem.attr('data-count', notificationsCount);
    notificationsWrapper.find('.notif-count').text(notificationsCount);
    notificationsWrapper.show();
});
