import UserNotifications

UNUserNotificationCenter.current().requestAuthorization(options: [.alert]) { _, _ in }