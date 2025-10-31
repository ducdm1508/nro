package services.dungeon;

import models.dungeon.Boss22h;
import models.map.Zone;
import models.player.Player;
import services.map.ChangeMapService;
import services.map.MapService;
import utils.Util;

import java.util.ArrayList;
import java.util.List;

public class Boss22HService {

    private static Boss22HService instance;

    public static Boss22HService getInstance() {
        if (instance == null) {
            instance = new Boss22HService();
        }
        return instance;
    }

    private final List<Boss22h> boss22Hs;

    private Boss22HService() {
        this.boss22Hs = new ArrayList<>();
        for (int i = 0; i < Boss22h.AVAILABLE; i++) {
            this.boss22Hs.add(new Boss22h(i));
        }
    }

    public void addMapBoss22H(int id, Zone zone) {
        if (zone.map.mapId == 126) {
            this.boss22Hs.get(id).getZones().add(zone);
        }
    }

    public void joinBoss22H(Player player) {
        if (player.zone != null && player.zone.map.mapId == 126) {
            return;
        }

        List<Zone> availableZones = new ArrayList<>();

        for (Boss22h boss : this.boss22Hs) {
            for (Zone zone : boss.getZones()) {
                if (zone.getNumOfPlayers() < 5 && zone.map.mapId == 126) {
                    availableZones.add(zone);
                }
            }
        }

        Zone targetZone = availableZones.isEmpty()
                ? MapService.gI().getMapWithRandZone(126)
                : availableZones.get(Util.nextInt(0, availableZones.size() - 1));

        ChangeMapService.gI().changeMap(player, targetZone, -1, 312);
    }
}