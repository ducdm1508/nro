package server;

import consts.ConstNpc;
import managers.GiftCodeManager;
import managers.ShenronEventManager;
import managers.boss.*;
import models.ShenronEvent;
import models.item.Item;
import models.player.Pet;
import models.player.Player;
import models.player.badges.BadgesData;
import network.session.SessionManager;
import services.*;
import services.func.Input;
import services.map.ChangeMapService;
import services.map.NpcService;
import services.player.InventoryService;
import utils.SystemMetrics;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.function.BiConsumer;
import java.util.function.Consumer;

public class Command {

    private static Command instance;

    private final Map<String, Consumer<Player>> adminCommands = new HashMap<>();
    private final Map<String, BiConsumer<Player, String>> parameterizedCommands = new HashMap<>();

    public static Command gI() {
        if (instance == null) {
            instance = new Command();
        }
        return instance;
    }

    private Command() {
        initAdminCommands();
        initParameterizedCommands();
    }

    private void initAdminCommands() {
        adminCommands.put("giftcode", player -> GiftCodeManager.gI().checkInfomationGiftCode(player));
        adminCommands.put("listboss", player -> BossManager.gI().showListBoss(player));
        adminCommands.put("lbb", player -> BrolyManager.gI().showListBoss(player));
        adminCommands.put("lbpb", player -> OtherBossManager.gI().showListBoss(player));
        adminCommands.put("lbdt", player -> RedRibbonHQManager.gI().showListBoss(player));
        adminCommands.put("lbbdkb", player -> TreasureUnderSeaManager.gI().showListBoss(player));
        adminCommands.put("lbcdrd", player -> SnakeWayManager.gI().showListBoss(player));
        adminCommands.put("lbkghd", player -> GasDestroyManager.gI().showListBoss(player));
        adminCommands.put("lbtt", player -> TrungThuEventManager.gI().showListBoss(player));
        adminCommands.put("menu", player -> NpcService.gI().createMenuConMeo(player, ConstNpc.MENU_ADMIN, -1,
                "|0|Time start: " + ServerManager.timeStart + "\nClients: " + Client.gI().getPlayers().size() +
                        " người chơi\n Sessions: " + SessionManager.gI().getNumSession() + "\nThreads: " + Thread.activeCount() +
                        " luồng" + "\n" + SystemMetrics.ToString(),
                "Ngọc rồng", "Đệ tử", "Bảo trì", "Tìm kiếm\nngười chơi", "Boss", "Đóng"));
        adminCommands.put("giveitem", player -> Input.gI().createFormGiveItem(player));
        adminCommands.put("item", player -> Input.gI().createFormGetItem(player));
        adminCommands.put("d", player -> Service.gI().setPos(player, player.location.x, player.location.y + 10));
        adminCommands.put("rs", player -> Service.gI().releaseCooldownSkill(player));

    }

    private void initParameterizedCommands() {
        parameterizedCommands.put("m ", (player, text) -> {
            int mapId = Integer.parseInt(text.replace("m ", ""));
            ChangeMapService.gI().changeMapInYard(player, mapId, -1, -1);
        });

        parameterizedCommands.put("i ", (player, text) -> {
            int itemId = Integer.parseInt(text.replace("i ", ""));
            Item item = ItemService.gI().createNewItem(((short) itemId));
            List<Item.ItemOption> ops = ItemService.gI().getListOptionItemShop((short) itemId);
            if (!ops.isEmpty()) {
                item.itemOptions = ops;
            }
            InventoryService.gI().addItemBag(player, item);
            InventoryService.gI().sendItemBags(player);
            Service.gI().sendThongBao(player, "GET " + item.template.name + " [" + item.template.id + "] SUCCESS !");
        });
    }

    public void chat(Player player, String text) {
        if (!check(player, text)) {
            Service.gI().chat(player, text);
        }
    }

    public boolean check(Player player, String text) {
        try {
            if (player.isAdmin()) {
                if (adminCommands.containsKey(text)) {
                    adminCommands.get(text).accept(player);
                    return true;
                }
                if (text.equals("gt")) {
                    GiftCodeManager.gI().checkInfomationGiftCode(player);
                    return true;
                } else if (text.equals("a")) {
                    BossManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("b")) {
                    BrolyManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("mapboss2")) {
                    OtherBossManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("mapdt")) {
                    RedRibbonHQManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("mapbdkb")) {
                    TreasureUnderSeaManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("mapcdrd")) {
                    SnakeWayManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("mapkghd")) {
                    GasDestroyManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("maptrungthu")) {
                    TrungThuEventManager.gI().showListBoss(player);
                    return true;
                } else if (text.equals("r")) {
                    Service.gI().releaseCooldownSkill(player);
                    return true;
                } else if (text.startsWith("sp")) {
                    try {
                        long power = Long.parseLong(text.replaceAll("up", ""));
                        Service.gI().addSMTN(player, (byte) 2, power, false);
                        return true;
                    } catch (Exception e) {
                    }
                } else if (text.startsWith("dt")) {
                    try {
                        long power = Long.parseLong(text.replaceAll("upp", ""));
                        Service.gI().addSMTN(player.pet, (byte) 2, power, false);
                        return true;
                    } catch (Exception e) {
                    }

                } else if (text.equals("dragon")) {
                    ShenronEvent shenron = new ShenronEvent();
                    shenron.setPlayer(player);
                    ShenronEventManager.gI().add(shenron);
                    player.shenronEvent = shenron;
                    shenron.setZone(player.zone);
                    shenron.activeShenron(true, ShenronEvent.DRAGON_EVENT);
                    shenron.sendWhishesShenron();
                    return true;
                }
                if (text.startsWith("dmg")) {
                    try {
                        int dameg = Integer.parseInt(text.replaceAll("dmg", ""));
                        player.nPoint.dameg = dameg;
                        Service.gI().point(player);
                        return true;
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                if (text.startsWith("hpg")) {
                    try {
                        int hpg = Integer.parseInt(text.replaceAll("hpg", ""));
                        player.nPoint.hpg = hpg;
                        Service.gI().point(player);
                        return true;
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                if (text.startsWith("kig")) {
                    try {
                        int mpg = Integer.parseInt(text.replaceAll("kig", ""));
                        player.nPoint.mpg = mpg;
                        Service.gI().point(player);
                        return true;
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                if (text.startsWith("defg")) {
                    try {
                        int defg = Integer.parseInt(text.replaceAll("defg", ""));
                        player.nPoint.defg = defg;
                        Service.gI().point(player);
                        return true;
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                if (text.startsWith("crg")) {
                    try {
                        int critg = Integer.parseInt(text.replaceAll("crg", ""));
                        player.nPoint.critg = critg;
                        Service.gI().point(player);
                        return true;
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                if (text.startsWith("ntask")) {
                    try {
                        int idTask = Integer.parseInt(text.replaceAll("ntask", ""));
                        player.playerTask.taskMain.id = idTask - 1;
                        player.playerTask.taskMain.index = 0;
                        TaskService.gI().sendNextTaskMain(player);
                        return true;
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                if (text.startsWith("badges_")) {
                    int idBadges = Integer.parseInt(text.replaceAll("badges_", ""));
                    player.badges.idBadges = idBadges;
                }

                if (text.startsWith("danhhieu_")) {
                    int idGender = Integer.parseInt(text.replaceAll("danhhieu_", ""));
                    BadgesData data = new BadgesData(player, idGender, 5);
                    return true;
                }
                if (text.startsWith("gender_")) {
                    byte idGender = Byte.parseByte(text.replaceAll("gender_", ""));
                    player.gender = idGender;
                    return true;
                }

                } else if (text.equals("item")) {
                    Input.gI().createFormGiveItem(player);
                    return true;
                } else if (text.equals("getitem")) {
                    Input.gI().createFormGetItem(player);
                    return true;
                } else if (text.equals("d")) {
                    Service.gI().setPos(player, player.location.x, player.location.y + 10);
                    return true;
                }

            if (text.startsWith("ten con la ")) {
                PetService.gI().changeNamePet(player, text.replaceAll("ten con la ", ""));
            }

            if (player.pet != null) {
                switch (text) {
                    case "di theo", "follow" ->
                            player.pet.changeStatus(Pet.FOLLOW);
                    case "bao ve", "protect" ->
                            player.pet.changeStatus(Pet.PROTECT);
                    case "tan cong", "attack" ->
                            player.pet.changeStatus(Pet.ATTACK);
                    case "ve nha", "go home" ->
                            player.pet.changeStatus(Pet.GOHOME);
                    case "bien hinh" ->
                            player.pet.transform();
                }
            }
            return false;
        } catch (Exception e) {
            return false;
        }
    }
}