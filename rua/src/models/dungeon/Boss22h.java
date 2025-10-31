package models.dungeon;

import lombok.Data;
import models.map.Zone;
import models.player.Player;
import server.Maintenance;
import services.map.ChangeMapService;
import services.map.MapService;
import utils.Functions;
import utils.TimeUtil;
import utils.Util;

import java.util.ArrayList;
import java.util.List;

@Data
public final class Boss22h implements Runnable {

    public static final int AVAILABLE = 10;
    private final int id;
    private final List<Zone> zones;
    private boolean hasKicked = false; // Đánh dấu đã kick để tránh kick lại

    public Boss22h(int id) {
        this.id = id;
        this.zones = new ArrayList<>();
        this.init();
    }

    public void init() {
        new Thread(this, "Boss 22H - ID: " + id).start();
    }

    @Override
    public void run() {
        while (!Maintenance.isRunning) {
            try {
                long startTime = System.currentTimeMillis();
                update();
                // Tăng sleep time lên để giảm tải CPU
                Functions.sleep(Math.max(1000 - (System.currentTimeMillis() - startTime), 100));
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    public void update() {
        boolean is22H = TimeUtil.is22H();

        if (is22H) {
            // Trong giờ 22h - reset flag để có thể kick khi hết giờ
            hasKicked = false;
        } else {
            // Ngoài giờ 22h - chỉ kick 1 LẦN DUY NHẤT
            if (!hasKicked) {
                finish();
                hasKicked = true; // Đánh dấu đã kick, không kick nữa
            }
        }
    }

    private void finish() {
        int totalKicked = 0;

        for (int j = zones.size() - 1; j >= 0; j--) {
            Zone zone = zones.get(j);

            // Tạo list copy để tránh ConcurrentModificationException
            List<Player> playersToKick = new ArrayList<>(zone.getPlayers());

            for (Player pl : playersToKick) {
                if (pl != null && !pl.isAdmin()) {
                    kickOut(pl);
                    totalKicked++;
                }
            }
        }

        if (totalKicked > 0) {

        }
    }

    public Zone getMapById(int mapId) {
        for (Zone zone : this.zones) {
            if (zone.map.mapId == mapId) {
                return zone;
            }
        }
        return null;
    }

    private void kickOut(Player player) {
        try {
            if (player.zone != null && MapService.gI().isMapBoss22H(player.zone.map.mapId)) {
                ChangeMapService.gI().changeMapBySpaceShip(player, player.gender + 21, -1, 336);
            }
        } catch (Exception e) {

        }
    }

    public boolean isActive() {
        return TimeUtil.is22H();
    }
}