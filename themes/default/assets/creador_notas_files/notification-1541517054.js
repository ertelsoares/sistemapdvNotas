var NotificationManager = {};
NotificationManager.conn = null;
NotificationManager.connParams = {'skipSubprotocolCheck': true};
NotificationManager.channels = [];
NotificationManager.idUser = 0;
NotificationManager.live = null;
NotificationManager.notificationMenu = null;
NotificationManager.notificationHistoryLoad = false;
NotificationManager.notificationsHistory = {};
NotificationManager.notificationsCount = 0;
NotificationManager.supportSharedConn = false;
NotificationManager.unreadNotificationLoad = false;
NotificationManager.unreadNotifications = {};

NotificationManager.enabled = true;

NotificationManager.init = function() {
	NotificationManager.notificationMenu = $('#notificationMenu');
	NotificationManager.enabled = NotificationManager.notificationMenu.length;

	$(document).mouseup(function(e) {
		if (!NotificationManager.notificationMenu.is(e.target) && NotificationManager.notificationMenu.has(e.target).length == 0 && !$('#ico_notification').is(e.target) && NotificationManager.notificationMenu.is(':visible')) {
			NotificationManager.notificationMenu.hide(0, NotificationManager.hideAll);
			NotificationManager.menuToggle('close');
		}
	})
	.on('click', '#bell_content', function() {
		NotificationManager.getUnreadNotifications(function() {
			NotificationManager.notificationMenu.toggle(0, NotificationManager.menuToggle);
		});
	})
	.on('click', '[data-action="read-notification"]', function(e) {
		if (!$(e.target).is('li')) {
			NotificationManager.toggleNotifications($(this));
			NotificationManager.readNotifications($(this).attr('data-group'));
		}
	})
	.on('click', '[data-action="show-notification"]', function(e) {
		if (!$(e.target).is('li')) {
			NotificationManager.toggleNotifications($(this));
		}
	})
	.on('click', '#notification-history-title', function(){
		if ($('#n_list_history').parent().find('img[name="n_loading"]').length == 0) {
			$('#n_list_history').parent().append('<img name="n_loading" class="center-image margin_top10" src="images/load.gif"/>');
		}

		$('#n_list_history').parent().toggle(0, function() {
			NotificationManager.switchCaret();
		});

		NotificationManager.getNotificationsHistory(function() {
			$('#n_list_history').parent().find('img[name="n_loading"]').remove();
		});
	});

	NotificationManager.connect();
};

NotificationManager.connect = function() {
	$.post('utils/requestMethods.php', {'action': 'getNChannels'}, function(data) {
		var channelsData = NotificationManager.jsonDecode(data);
		NotificationManager.WS_URL = channelsData.wsURL;
		NotificationManager.setIdUser(channelsData.idUser);
		NotificationManager.initNotificationsCount(channelsData.unreadNotifications);
		NotificationManager.channels = channelsData.channels;

		NotificationManager.wsConnect();
	});
};

NotificationManager.wsConnect = function() {
	NotificationManager.supportSharedConn = NotificationManager.browserSupportSharedConn();
	var connOptions = {retry: 60};

	if (NotificationManager.supportSharedConn) {
		NotificationManager.live = tabex.client();
		NotificationManager.live.on('!sys.master', function(data) {
			if (data.node_id === data.master_id) {
				if (!NotificationManager.conn) {
					NotificationManager.conn = new Faye.Client(NotificationManager.WS_URL, connOptions);
					NotificationManager.onOpen();
				}
			} else {
				if (NotificationManager.conn) {
					NotificationManager.conn.disconnect();
					NotificationManager.conn = null;
				}
			}
		});

		NotificationManager.live.on('notifyTabs', function(data) {
			NotificationManager.showUnreadNotification(data, true);
		});

		NotificationManager.live.on('setIdUser', function(idUser) {
			NotificationManager.idUser = idUser;
		});

		NotificationManager.live.on('clearReadNotifications', function(groupId) {
			NotificationManager.clearReadNotifications(groupId);
		});
	} else {
		NotificationManager.conn = new Faye.Client(NotificationManager.WS_URL, connOptions);
		NotificationManager.onOpen();
	}
};

NotificationManager.onOpen = function() {
	if (NotificationManager.conn && NotificationManager.enabled) {
		NotificationManager.conn.subscribe(NotificationManager.channels, NotificationManager.onSubscribe);
	}
};

NotificationManager.onClose = function() {
	console.warn('WebSocket connection closed');
};

NotificationManager.onSubscribe = function(data) {
	NotificationManager.showUnreadNotification(data, true);
	NotificationManager.tabsEmit('notifyTabs', data);
};

NotificationManager.setIdUser = function(idUser) {
	NotificationManager.idUser = idUser;
	NotificationManager.tabsEmit('setIdUser', idUser);
};

NotificationManager.getNotificationsHistory = function(callback) {
	if (NotificationManager.notificationHistoryLoad) {
		callback();
		return;
	}

	$.post('utils/requestMethods.php', {'action': 'getNotificationsHistory'}, function(data) {
		var notificationsData = NotificationManager.jsonDecode(data);

		$.each(notificationsData, function(i, notificationHistory) {
			NotificationManager.showNotificationHistory(notificationHistory, false);
		});

		NotificationManager.updateGroupsLastDate($('#n_list_history'), NotificationManager.notificationsHistory);
		NotificationManager.notificationHistoryLoad = true;

		callback();
	});
};

NotificationManager.getUnreadNotifications = function(callback) {
	if (NotificationManager.unreadNotificationLoad) {
		callback();
		return;
	}

	NotificationManager.unreadNotificationLoad = true;
	$.post('utils/requestMethods.php', {'action': 'getUnreadNotifications'}, function(data) {
		var notificationsData = NotificationManager.jsonDecode(data);

		$.each(notificationsData, function(i, unreadNotification) {
			NotificationManager.showUnreadNotification(unreadNotification, false);
		});

		NotificationManager.initNotificationsCount();
		callback();
	});
};

NotificationManager.showNotificationHistory = function(data) {
	var notification = data;
	var notYetNotified = $.isEmptyObject(arrayJsonSearch(NotificationManager.notificationsHistory[notification.message.type], 'id', notification.id));

	if (!notification.isNew && notYetNotified && NotificationManager.idUser == notification.idUsuario) {
		NotificationManager.addNotification(NotificationManager.notificationsHistory, notification);
		NotificationManager.renderNotificationHistory(notification);
	}
};

NotificationManager.showUnreadNotification = function(data, updateGroupsCount) {
	var notification = data;
	var notYetNotified = $.isEmptyObject(arrayJsonSearch(NotificationManager.unreadNotifications[notification.message.type], 'id', notification.id));

	if (notification.isNew && notYetNotified && NotificationManager.idUser == notification.idUsuario) {
		NotificationManager.addNotification(NotificationManager.unreadNotifications, notification);
		NotificationManager.renderUnreadNotification(notification);

		if (updateGroupsCount) {
			NotificationManager.updateGroupsCount(notification.message.type);
		}
	}
};

NotificationManager.readNotifications = function(groupId) {
	if (NotificationManager.unreadNotifications[groupId].length > 0) {
		var notificationsIds = [];

		$.each(NotificationManager.unreadNotifications[groupId], function() {
			notificationsIds.push(this.id);
		});

		$.post('utils/requestMethods.php', {'action': 'rn', 'nIds': notificationsIds}, function() {
			NotificationManager.clearReadNotifications(groupId);
			NotificationManager.tabsEmit('clearReadNotifications', groupId);
		});
	}
};

NotificationManager.clearReadNotifications = function(groupId) {
	NotificationManager.unreadNotifications[groupId] = [];
	NotificationManager.updateGroupsCount(groupId, true);
};

NotificationManager.addNotification = function(notificationList, notification) {
	if (!Array.isArray(notificationList[notification.message.type])) {
		notificationList[notification.message.type] = [];
	}

	notificationList[notification.message.type].push(notification);
};

NotificationManager.tabsEmit = function(e, data, incSelf) {
	if (NotificationManager.supportSharedConn) {
		NotificationManager.live.emit(e, data, (incSelf | false));
	}
};

NotificationManager.renderUnreadNotification = function(notification) {
	NotificationManager.renderNotification($('#n_list_unread'), notification, 'read-notification');
	$('#no-notification-box').hide();
};

NotificationManager.renderNotificationHistory = function(notification) {
	NotificationManager.renderNotification($('#n_list_history'), notification, 'show-notification');
};

NotificationManager.renderNotification = function(notificationListElement, notification, action) {
	var notificationGroupId = notification.message.type;
	var notificationGroup = notificationListElement.find('li[data-group=' + notificationGroupId + '] ul:eq(0)');

	if (!notificationGroup.length) {
		notificationGroup = $('<ul>', {'style': 'display: none'});
		notificationDescription = $('<div>', {'class': 'notification-box-content'}).append(
			$('<span>', {'text': notification.message.typeInfo, 'class': 'notification-group-title'}),
			$('<span>', {'data-group-sum': notification.message.type}).append($('<span>'), $('<i>', {class: 'ico-caret-right'})),
			$('<div>', {'class': 'notification-date'})
		);
		notificationListElement.append(
			$('<li>', {'data-action': action, 'data-group': notificationGroupId}).append(
				notificationDescription,
				notificationGroup
			)
		);
	}

	notificationGroup.prepend(
		$('<li>', {'text': notification.message.text}).append($('<hr>'))
	);
};

NotificationManager.toggleNotifications = function(element) {
	var icoElement = element.find('i:eq(0)');
	var notificationList = element.find('ul:eq(0)');

	if (element.attr('data-expanded') == 'true') {
		icoElement.attr('class', 'ico-caret-right');
		notificationList.hide();
		element.attr('data-expanded', 'false');
	} else {
		icoElement.attr('class', 'ico-caret-down');
		notificationList.show();
		element.attr('data-expanded', 'true');
	}
};

NotificationManager.initNotificationsCount = function(sum) {
	if (typeof sum == 'undefined') {
		NotificationManager.updateGroupsCount();
	} else {
		NotificationManager.notificationsCount = sum;
		NotificationManager.showNotificationsCount();
	}
};

NotificationManager.updateGroupsCount = function(updateNotificationsCount, read) {
	var currNotificationsCount = NotificationManager.notificationsCount;
	if (NotificationManager.unreadNotificationLoad || read) {
		NotificationManager.notificationsCount = 0;

		for (var groupId in NotificationManager.unreadNotifications) {
			var groupSum = NotificationManager.unreadNotifications[groupId].length;

			$('#n_list_unread [data-group-sum=' + groupId + '] span').text((groupSum ? groupSum : ''));
			NotificationManager.notificationsCount += groupSum;
		}
	} else {
		NotificationManager.notificationsCount += 1;
	}

	if (!updateNotificationsCount) {
		NotificationManager.notificationsCount = currNotificationsCount;
	}

	NotificationManager.updateGroupsLastDate($('#n_list_unread'), NotificationManager.unreadNotifications);
	NotificationManager.showNotificationsCount();
};

NotificationManager.updateGroupsLastDate = function(notificationListElement, notifications) {
	for (var groupId in notifications) {
		var lastIndex = notifications[groupId].length - 1;
		var lastNotification = notifications[groupId][lastIndex];

		if (lastNotification) {
			notificationListElement.find('[data-group=' + groupId + '] .notification-date').text(NotificationManager.parseDateInfo(lastNotification.message.creationDate));
		}
	}
};

NotificationManager.showNotificationsCount = function() {
	if (!NotificationManager.enabled) {
		return;
	}

	var currTitleSplit = $('title').text().split(') ');
	var newTitle = (currTitleSplit[1] ?  currTitleSplit[1] : currTitleSplit[0]);

	if (NotificationManager.notificationsCount > 0) {
		var notificationSum = (NotificationManager.notificationsCount > 99 ? '+99' : NotificationManager.notificationsCount);

		$('#bell_content span').show().text(notificationSum);
		newTitle = '(' + notificationSum  + ') ' + newTitle;
	} else {
		$('#bell_content span').hide();
	}

	$('title').text(newTitle);
};

NotificationManager.parseDateInfo = function(date) {
	var dateSplit = date.split(' ');
	var date = dateSplit[0].split('-');
	var time = dateSplit[1].split(':');
	var months = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

	var day = date[2];
	var month = months[parseInt(date[1]) - 1];
	var hour = time[0] + ':' + time[1];

	return day + ' de ' + month + ' - ' + hour;
};

NotificationManager.hideAll = function() {
	$('#n_list_history').parent().hide(0, function() {
		NotificationManager.switchCaret(true);
	});
};

NotificationManager.switchCaret = function(hide) {
	var caretIco = $('#notification-history-title').find('i');

	if(caretIco.hasClass('ico-caret-right') && !hide) {
		caretIco.removeClass('ico-caret-right').addClass('ico-caret-down');
	} else {
		caretIco.removeClass('ico-caret-down').addClass('ico-caret-right');
	}
};

NotificationManager.browserSupportSharedConn = function() {
	BrowserDetect.init();
	return (!(BrowserDetect.browser == 'Edge' || BrowserDetect.browser == 'Explorer') && typeof(Worker) !== "undefined" && NotificationManager.supportLocalStorage());
};

NotificationManager.supportLocalStorage = function() {
	var mod = 'test';

	try {
		localStorage.setItem(mod, mod);
		localStorage.removeItem(mod);

		return true;
	} catch (e) {
		return false;
	}
};

NotificationManager.jsonDecode = function(stringJson) {
	try {
		return JSON.parse(stringJson);
	} catch(e) {
		return [];
	}
}

NotificationManager.menuToggle = function(opt) {
	var isOpen;

	switch(opt) {
	case 'close':
		NotificationManager.notificationMenu.attr('data-open', 0);
		break;
	case 'open':
		NotificationManager.notificationMenu.attr('data-open', 1);
		break;
	default:
		isOpen = parseInt(NotificationManager.notificationMenu.attr('data-open') || 0);

		if (isOpen) {
			NotificationManager.notificationMenu.attr('data-open', 0);
		} else {
			NotificationManager.notificationMenu.attr('data-open', 1);
		}
	}

	isOpen = parseInt(NotificationManager.notificationMenu.attr('data-open'));
	if (NotificationManager.notificationMenu.hasClass('mobile')) {
		if(isOpen && $("#modalWait").length == 0) {
			$('body').append($('<div>', {'id': 'modalWait'}));
		} else {
			$('#modalWait').remove();
		}
	}
}