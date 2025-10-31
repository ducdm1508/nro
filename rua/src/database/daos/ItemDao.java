package database.daos;

import database.AlyraManager;
import models.Template;
import models.item.Item;
import services.ItemService;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class ItemDao {

    public static Item getItemOptions(short item, int qty) throws SQLException {
        Connection conn = AlyraManager.getConnection();
        PreparedStatement ps = conn.prepareStatement("SELECT * FROM item_options WHERE item_id = ?");
        ps.setShort(1, item);
        ResultSet rs = ps.executeQuery();

        Item items = new Item();
        items.quantity = qty;
        items.template = ItemService.gI().getTemplate(item);

        while (rs.next()) {
            int optionId = rs.getInt("option_id");
            int param = rs.getInt("param");

            Item.ItemOption option = new Item.ItemOption(optionId, (int) param);
            items.itemOptions.add(option);
        }

        rs.close();
        ps.close();
        conn.close();

        return items; // ✅ Trả về đối tượng Item
    }

}
