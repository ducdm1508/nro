package services;




import consts.ConstPlayer;
import models.player.NewPet;
import models.player.Pet;
import models.player.Player;
import services.map.ChangeMapService;
import services.player.InventoryService;
import utils.SkillUtil;
import utils.Util;

public class PetService {

    private static PetService instance;

    public static PetService gI() {
        if (instance == null) {
            instance = new PetService();
        }
        return instance;
    }

    public void createNormalPet(Player player, int gender, byte... limitPower) {
        new Thread(() -> {
            try {
                // Tạo pet thường - typePet = 0
                createNewPet(player, (byte) 0, (byte) gender);

                // Nếu có truyền limitPower thì gán
                if (limitPower != null && limitPower.length == 1) {
                    player.pet.nPoint.limitPower = limitPower[0];
                }

                // Chờ 1 giây rồi hiển thị lời chào
                Thread.sleep(1000);
                Service.gI().chatJustForMe(player, player.pet, "Xin hãy thu nhận làm đệ tử");
            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();

    }

    public void createNormalPet(Player player, byte... limitPower) {
        new Thread(() -> {
            try {
                // Không truyền giới tính => random
                createNewPet(player, (byte) 0);

                if (limitPower != null && limitPower.length == 1) {
                    player.pet.nPoint.limitPower = limitPower[0];
                }

                Thread.sleep(1000);
                Service.gI().chatJustForMe(player, player.pet, "Xin hãy thu nhận làm đệ tử");
            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
    }


    public void createMabuPet(Player player, byte... limitPower) {
        new Thread(() -> {
            try {
                // Tạo pet Mabư (typePet = 1)
                createNewPet(player, (byte) 1);

                // Nếu có limitPower được truyền thì gán cho pet
                if (limitPower != null && limitPower.length == 1) {
                    player.pet.nPoint.limitPower = limitPower[0];
                }

                // Chờ 1 giây để tạo hiệu ứng xuất hiện tự nhiên
                Thread.sleep(1000);

                // Mabư xuất hiện với câu thoại riêng
                Service.gI().chatJustForMe(player, player.pet, "Oa oa oa...");
            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
    }


    public void createMabuPet(Player player, int gender, byte... limitPower) {
        new Thread(() -> {
            try {
                // Tạo pet Mabư (typePet = 1)
                createNewPet(player, (byte) 1, (byte) gender);

                // Nếu có giới hạn sức mạnh thì gán
                if (limitPower != null && limitPower.length == 1) {
                    player.pet.nPoint.limitPower = limitPower[0];
                }

                // Chờ 1 giây rồi cho pet "chào" chủ
                Thread.sleep(1000);
                Service.gI().chatJustForMe(player, player.pet, "Oa oa oa...");
            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
    }


    public void changeNormalPet(Player player, int gender) {
        byte limitPower = player.pet.nPoint.limitPower;
        if (player.fusion.typeFusion != ConstPlayer.NON_FUSION) {
            player.pet.unFusion();
        }
        ChangeMapService.gI().exitMap(player.pet);
        player.pet.dispose();
        player.pet = null;
        createNormalPet(player, gender, limitPower);
    }

    public void createPet2(Player player, byte... limitPower) {
        new Thread(() -> {
            try {
                // Tạo pet thường - typePet = 0
                createNewPet(player, (byte) 2);

                // Nếu có truyền limitPower thì gán
                if (limitPower != null && limitPower.length == 1) {
                    player.pet.nPoint.limitPower = limitPower[0];
                }

                // Chờ 1 giây rồi hiển thị lời chào
                Thread.sleep(1000);
                Service.gI().chatJustForMe(player, player.pet, "Xin hãy thu nhận làm đệ tử");
            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
        ChangeMapService.gI().exitMap(player.pet);
        player.pet.dispose();
        player.pet = null;
    }

    public void changeNormalPet(Player player) {
        byte limitPower = player.pet.nPoint.limitPower;
        if (player.fusion.typeFusion != ConstPlayer.NON_FUSION) {
            player.pet.unFusion();
        }
        ChangeMapService.gI().exitMap(player.pet);
        player.pet.dispose();
        player.pet = null;
        createNormalPet(player, limitPower);
    }

    public void changeMabuPet(Player player) {
        byte limitPower = player.pet.nPoint.limitPower;
        if (player.fusion.typeFusion != ConstPlayer.NON_FUSION) {
            player.pet.unFusion();
        }
        ChangeMapService.gI().exitMap(player.pet);
        player.pet.dispose();
        player.pet = null;
        createMabuPet(player, limitPower);
    }

    public void changeMabuPet(Player player, int gender) {
        byte limitPower = player.pet.nPoint.limitPower;
        if (player.fusion.typeFusion != ConstPlayer.NON_FUSION) {
            player.pet.unFusion();
        }
        ChangeMapService.gI().exitMap(player.pet);
        player.pet.dispose();
        player.pet = null;
        createMabuPet(player, gender, limitPower);
    }

    public void changeNamePet(Player player, String name) {
        try {
            if (!InventoryService.gI().isExistItemBag(player, 400)) {
                Service.gI().sendThongBao(player, "Bạn cần thẻ đặt tên đệ tử, mua tại Santa");
                return;
            } else if (Util.haveSpecialCharacter(name)) {
                Service.gI().sendThongBao(player, "Tên không được chứa ký tự đặc biệt");
                return;
            } else if (name.length() > 10) {
                Service.gI().sendThongBao(player, "Tên quá dài");
                return;
            }
            ChangeMapService.gI().exitMap(player.pet);
            player.pet.name = "$" + name.toLowerCase().trim();
            InventoryService.gI().subQuantityItemsBag(player, InventoryService.gI().findItemBag(player, 400), 1);
            new Thread(() -> {
                try {
                    Thread.sleep(1000);
                    Service.gI().chatJustForMe(player, player.pet, "Cảm ơn sư phụ đã đặt cho con tên " + name);
                } catch (Exception e) {
                }
            }).start();
        } catch (Exception ex) {

        }
    }

    private int[] getDataPetNormal() {
        int[] petData = new int[5];
        petData[0] = Util.nextInt(40, 105) * 20; //hp
        petData[1] = Util.nextInt(40, 105) * 20; //mp
        petData[2] = Util.nextInt(20, 45); //dame
        petData[3] = Util.nextInt(9, 50); //def
        petData[4] = Util.nextInt(0, 2); //crit
        return petData;
    }

    private int[] getDataPetMabu() {
        int[] petData = new int[5];
        petData[0] = Util.nextInt(40, 105) * 20; //hp
        petData[1] = Util.nextInt(40, 105) * 20; //mp
        petData[2] = Util.nextInt(50, 120); //dame
        petData[3] = Util.nextInt(9, 50); //def
        petData[4] = Util.nextInt(0, 2); //crit
        return petData;
    }

    private int[] getDataPet2() {
        int[] petData = new int[5];
        petData[0] = 747500; //hp
        petData[1] = 747500; //mp
        petData[2] = 33800; //dame
        petData[3] = Util.nextInt(750, 950); //def
        petData[4] = Util.nextInt(0, 2); //crit
        return petData;
    }

    private void createNewPet(Player player, byte typePet, byte... gender) {
        // Chọn dữ liệu chỉ số theo loại pet
        int[] data;
        switch (typePet) {
            case 1:
                data = getDataPetMabu();   // type 1 - Mabư
                break;
            case 2:
                data = getDataPet2();      // type 2 - Pet đặc biệt
                break;
            default:
                data = getDataPetNormal(); // type 0 - Pet thường
                break;
        }

        // Khởi tạo pet
        Pet pet = new Pet(player);
        String oldName = (player.pet != null) ? player.pet.name : "Đệ tử";
        pet.name = "$" + (typePet == 1 ? "Mabư" : typePet == 2 ? oldName : "Đệ tử");

        pet.gender = (gender != null && gender.length > 0) ? gender[0] : (byte) Util.nextInt(0, 2);
        pet.id = player.isPl() ? -player.id : -Math.abs(player.id) - 100000;

        // Chỉ số cơ bản
        pet.typePet = typePet;
        if (typePet == 0) {
            pet.nPoint.power = 2000; // Pet thường
        } else if (typePet == 1) {
            pet.nPoint.power = 1500000; // Mabu
        } else if (typePet == 2) {
            pet.nPoint.power = 40000000000L; // Pet 2 - cực mạnh
        }
        pet.nPoint.stamina = 1000;
        pet.nPoint.maxStamina = 1000;
        pet.nPoint.hpg = data[0];
        pet.nPoint.mpg = data[1];
        pet.nPoint.dameg = data[2];
        pet.nPoint.defg = data[3];
        pet.nPoint.critg = data[4];
        for (int i = 0; i < 7; i++) {
            pet.inventory.itemsBody.add(ItemService.gI().createItemNull());
        }
        pet.playerSkill.skills.add(SkillUtil.createSkill(Util.nextInt(0, 2) * 2, 1));
        if (pet.typePet == 2) {

            for (int i = 0; i < 4; i++) {
                pet.playerSkill.skills.add(SkillUtil.createEmptySkill());
            }
        } else {
            // Các pet khác chỉ có 4 skill
            for (int i = 0; i < 3; i++) {
                pet.playerSkill.skills.add(SkillUtil.createEmptySkill());
            }
        }

        pet.nPoint.setFullHpMp();
        player.pet = pet;
    }

    public static void Pet2(Player pl, int h, int b, int l) {
        if (pl.newPet != null) {
            pl.newPet.dispose();
        }
        pl.newPet = new NewPet(pl, (short) h, (short) b, (short) l);
        pl.newPet.name = "$" + pl.name.toLowerCase().trim() ;
        pl.newPet.gender = pl.gender;
        pl.newPet.nPoint.tiemNang = 1;
        pl.newPet.nPoint.power = 1;
        pl.newPet.nPoint.limitPower = 1;
        pl.newPet.nPoint.hpg = 50000000;
        pl.newPet.nPoint.mpg = 50000000;
        pl.newPet.nPoint.hp = 50000000;
        pl.newPet.nPoint.mp = 50000000;
        pl.newPet.nPoint.dameg = 1;
        pl.newPet.nPoint.defg = 1;
        pl.newPet.nPoint.critg = 1;
        pl.newPet.nPoint.stamina = 1;
        pl.newPet.nPoint.setBasePoint();
        pl.newPet.nPoint.setFullHpMp();
    }

}
