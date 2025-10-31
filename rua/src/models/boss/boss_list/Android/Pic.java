package models.boss.boss_list.Android;


import models.boss.Boss;
import consts.BossID;
import consts.BossStatus;
import models.boss.BossesData;
import models.map.ItemMap;
import models.player.Player;
import services.Service;
import services.TaskService;
import utils.Util;

import java.util.concurrent.ThreadLocalRandom;

public class Pic extends Boss {

    public Pic() throws Exception {
        super(BossID.PIC,false,true,  BossesData.PIC);
    }

    @Override
    public void reward(Player plKill) {
        int[] itemRan = new int[]{381, 382, 383, 384, 385, 17};
        int[] probabilities = {14, 16, 20, 20, 20, 10}; // Xác suất tương ứng của từng item

        int randomValue = ThreadLocalRandom.current().nextInt(100); // Random từ 0-99
        int accumulatedProbability = 0;
        int itemId = 380; // Mặc định

        for (int i = 0; i < itemRan.length; i++) {
            accumulatedProbability += probabilities[i];
            if (randomValue < accumulatedProbability) {
                itemId = itemRan[i];
                break;
            }
        }

        if (Util.isTrue(30, 100)) {
            ItemMap it = new ItemMap(this.zone, itemId, 1, this.location.x,
                    this.zone.map.yPhysicInTop(this.location.x, this.location.y - 24), plKill.id);
            Service.gI().dropItemMap(this.zone, it);
        }

        TaskService.gI().checkDoneTaskKillBoss(plKill, this);
    }

    @Override
    public void autoLeaveMap() {
        if (Util.canDoWithTime(st, 900000)) {
            this.leaveMapNew();
        }
        if (this.zone != null && this.zone.getNumOfPlayers() > 0) {
            st = System.currentTimeMillis();
        }
    }

    @Override
    public void joinMap() {
        super.joinMap(); //To change body of generated methods, choose Tools | Templates.
        st = System.currentTimeMillis();
    }
    private long st;

    @Override
    public void doneChatS() {
        this.changeStatus(BossStatus.AFK);
    }

    @Override
    public void doneChatE() {
        if (this.parentBoss == null) {
            return;
        }
        this.parentBoss.changeStatus(BossStatus.ACTIVE);
    }

}
