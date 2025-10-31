package models.npc.npc_list;

import models.npc.Npc;
import models.player.Player;
import services.Service;
import services.dungeon.Boss22HService;
import services.map.ChangeMapService;
import services.map.MapService;
import utils.TimeUtil;
import utils.Util;

import java.util.List;

public class Tapion extends Npc {

    public Tapion(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (canOpenNpc(player)) {
            if (mapId == 19) {
                this.createOtherMenu(player, 0, "Ác quỷ truyền thuyết Hirudegarn\nđã thoát khỏi phong ấn ngàn năm\nHãy giúp tôi chế ngự nó", "OK", "Từ chối");
            } else if (mapId == 126) {
                this.createOtherMenu(player, 0, "Tôi sẽ đưa bạn về", "OK", "Từ chối");
            }
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (canOpenNpc(player)) {
            switch (select) {
                case 0 -> {
                    if (mapId == 19) {
//                                if (DHVT.gI().Hour != Setting.TIME_START_HIRU_1 && DHVT.gI().Hour != Setting.TIME_START_HIRU_2) {
//                                    Service.gI().sendThongBao(player, "Hẹn gặp bạn lúc " + Setting.TIME_START_HIRU_1 + "h - " + Setting.TIME_START_HIRU_2 + "h mỗi ngày.");
//                                }
                        if (TimeUtil.is22H()){
                            Boss22HService.getInstance().joinBoss22H(player);
                        }else {;
                            Service.gI().sendThongBao(player, "Hẹn gặp bạn lúc 22h mỗi ngày. ");
                        }
                    } else if (mapId == 126) {
                        ChangeMapService.gI().changeMapNonSpaceship(player, 19, 1000 + Util.nextInt(-100, 100), 360);
                    }
                }
            }
        }
    }
}
