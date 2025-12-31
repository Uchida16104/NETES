import android.app.NotificationManager
import android.content.Context

fun notify(ctx: Context) {
    val nm = ctx.getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
}