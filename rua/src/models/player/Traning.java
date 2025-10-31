package models.player;



import lombok.Data;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Data
@NoArgsConstructor
public class Traning {

    private int playerID;

    private String name;

    private int top;


    private int topWhis;

    private int time;


    private long lastTime;


    private int lastTop;

    private long lastRewardTime;

    private int head;

    public void dispose() {
        name = null;
    }

}
