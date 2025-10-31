package event.event_list;



import consts.BossID;
import event.Event;

public class LunarNewYear extends Event {

    @Override
    public void npc() {
        createNpc(0, 49, 850, 432);
    }

    @Override
    public void boss() {
        createBoss(BossID.LAN_CON, 10);
    }
}
