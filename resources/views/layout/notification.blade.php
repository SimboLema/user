<style>


.loaderContainer{
  padding:50px;
  display: flex;
  justify-content: center;  /* Centers horizontally */
  align-items: center;      /* Centers vertically */ 
}
.loader5 {
  display:inline-block;
  width: 20px;
	height:20px;
	border-left: 20px solid transparent;
	border-right: 20px solid transparent;
	border-bottom: 20px solid #4183D7;
  border-top: 20px solid #F5AB35;
   -webkit-animation: loader5 1.2s ease-in-out infinite alternate;
   animation: loader5 1.2s ease-in-out infinite alternate;
}

@keyframes loader5 {
   from {transform: rotate(0deg);}
   to {transform: rotate(720deg);}
}
@-webkit-keyframes loader5 {
   from {-webkit-transform: rotate(0deg);}
   to {-webkit-transform: rotate(720deg);}
}
</style>
<div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="defaultModalLabel">{{ __('Notifications') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list-group list-group-flush my-n3" id="pusher-notifications">


        </div> <!-- / .list-group -->
      </div>
      <div class="modal-footer">
        <a  href="/notification" class="btn btn-secondary btn-block" >{{ __('See all notification') }}</a>
      </div>
    </div>
  </div>
</div>

<script>
  
  $(document).ready(function() {
  $(".notification-drop .item").on('click',function() {
    $(this).find('ul').toggle();
  });
});
</script>
<?php
use App\Models\PusherSetting;

$creatorUserId= Auth::user()->id ?? '';
$pusher_credentials = PusherSetting::where('archive',0)->first();

?>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
  const pusherKey = "<?php echo $pusher_credentials->key ?? '' ?>";
  const pusherCluster = "<?php echo $pusher_credentials->cluster ?? '' ?>";
  const pusherChannel = "<?php echo $pusher_credentials->channel ?? '' ?>";
  const creatorUserId = "<?php echo $creatorUserId ?? '' ?>";
  const unique_identification = pusherChannel+"-"+creatorUserId;

  var pusher = new Pusher(pusherKey, {
    cluster: pusherCluster,
  });

  var channel = pusher.subscribe("loan-"+unique_identification);
  channel.bind("notification-event", (e) => {
        getNotification()
        Alert.info(e.message,e.title,{displayDuration: 5000})
  });

  $(document).ready(function(){
    getNotification()
  })

  function getNotification(){
    localStorage.removeItem('notifications')
    jQuery.ajax({
      type: "GET",
      url: "/notification/getRecent",
      dataType: 'json',
      cache: false,
      success: function (data) {
        localStorage.setItem('notifications', JSON.stringify(data));
         showRecent()
      }
    });
  }

  
function showRecent() {
    var ul = document.getElementById('pusher-notifications');
    ul.innerHTML = ''; // Clear existing notifications if needed
    var notificationNumberSpan = document.getElementById('pusherNotificationNumber');
    

    var storedNotifications = localStorage.getItem('notifications');
    notificationList = storedNotifications ? JSON.parse(storedNotifications) : [];

    if (notificationList.length === 0) {
        // Remove the class and clear the span content if no notifications
        notificationNumberSpan.classList.remove('btn__badge', 'pulse-button');
        notificationNumberSpan.textContent = '';
        
    } else if (notificationList.length > 0) {
        // Add the class and update the span content with the notification count
        notificationNumberSpan.classList.add('btn__badge', 'pulse-button');
        notificationNumberSpan.textContent = notificationList.length;
    }

    // Append the "See all notifications" link at the end of the list
    var seeAllLink = '<li class="text-center text-green"><a href="/notification/index">{{ __('See all notifications') }}</a></li>';
    ul.innerHTML += seeAllLink;
    
    notificationList.reverse().forEach(function(notification, index) {
      var redirect_link=notification.redirect_link || "javascript:void(0)";
        // Create the HTML structure for each notification
        var li ='<div id="notification-' + notification.id + '" class="list-group-item bg-transparent">' +
            '<div class="row align-items-center">' +
            '<div class="col-auto">' +
              '<span class="fe fe-box fe-24"></span>' +
                '</div>' +
              '<div class="col"><a href="'+redirect_link+'">' +
              '<small><strong>{{ __('\' + notification.title + \'') }}</strong></small>' +
                '<div class="my-0 text-muted small">' + notification.message + '</div>' +
                '<small class="badge badge-pill badge-light text-muted">' + timeSince(new Date(notification.date)) + ' ago</small>' +
                '</a></div>' +
              '</div>' +
          '</div>'
        // onclick="removeNotification(' + notification.id + ')"
        // Append the new notification to the list
        ul.innerHTML += li;
    });
    
    

    // count notification

}

function timeSince(date) {
    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = Math.floor(seconds / 31536000);
    if (interval > 1) return interval + " years";

    interval = Math.floor(seconds / 2592000);
    if (interval > 1) return interval + " months";

    interval = Math.floor(seconds / 86400);
    if (interval > 1) return interval + " days";

    interval = Math.floor(seconds / 3600);
    if (interval > 1) return interval + " hours";

    interval = Math.floor(seconds / 60);
    if (interval > 1) return interval + " minutes";

    return Math.floor(seconds) + " seconds";
}


function removeNotification(notificationId) {
    var notificationElement = document.getElementById('notification-' + notificationId);
    if (notificationElement) {
        notificationElement.remove();
    }

    // Update localStorage
    var storedNotifications = localStorage.getItem('notifications');
    var notificationList = storedNotifications ? JSON.parse(storedNotifications) : [];
    var updatedNotifications = notificationList.filter(function(notification) {
        return notification.id !== notificationId;
    });

    localStorage.setItem('notifications', JSON.stringify(updatedNotifications));

    // Update notification count
    var notificationNumberSpan = document.getElementById('pusherNotificationNumber');
    notificationNumberSpan.textContent = updatedNotifications.length;

    if (updatedNotifications.length === 0) {
        notificationNumberSpan.classList.remove('btn__badge', 'pulse-button');
        notificationNumberSpan.textContent = '';
    }

    jQuery.ajax({
      type: "GET",
      url: "/notification/openNotification/"+notificationId,
      dataType: 'json',
      cache: false,
      success: function (data) {
         showRecent()
      }
    });
}

</script>