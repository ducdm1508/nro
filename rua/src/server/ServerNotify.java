package server;




import models.player.Player;
import network.io.Message;
import services.Service;
import utils.Util;
import java.util.ArrayList;
import java.util.List;

public class ServerNotify extends Thread {

    private long lastNotifyTime;

    private final List<String> notifies;

    private int indexNotify;

    private final String notify[] = {"Chào mừng anh em đến với server" + Manager.Name_SV};

    private static ServerNotify instance;

    private ServerNotify() {
        this.notifies = new ArrayList<>();
        this.start();
    }

    public static ServerNotify gI() {
        if (instance == null) {
            instance = new ServerNotify();
        }
        return instance;
    }

    @Override
    public void run() {
        while (!Maintenance.isRunning) {
            try {
                if (Util.canDoWithTime(this.lastNotifyTime, 1000)) {
                    sendChatVip(notify[indexNotify]);
                    this.lastNotifyTime = System.currentTimeMillis();
                    indexNotify++;
                    if (indexNotify >= notify.length) {
                        indexNotify = 0;
                    }
                }
                if (!notifies.isEmpty()) {
                    sendChatVip(notifies.removeFirst());
                }
            } catch (Exception ignored) {
            }
            try {
                Thread.sleep(1500);
            } catch (InterruptedException ignored) {
            }
        }
    }

    private void sendChatVip(String text) {
        Message msg;
        try {
            msg = new Message(93);
            msg.writer().writeUTF(text);
            Service.gI().sendMessAllPlayer(msg);
            msg.cleanup();
        } catch (Exception e) {
        }
    }

    public void notify(String text) {
        this.notifies.add(text);
    }

    public void sendNotifyTab(Player player) {
        Message msg;
        try {
            msg = new Message(50);
            msg.writer().writeByte(10);
            for (int i = 0; i < Manager.NOTIFY.size(); i++) {
                String[] arr = Manager.NOTIFY.get(i).split("<>");
                msg.writer().writeShort(i);
                msg.writer().writeUTF(arr[0]);
                msg.writer().writeUTF(arr[1]);
            }
            player.sendMessage(msg);
            msg.cleanup();
        } catch (Exception ignored) {
        }
    }
}
