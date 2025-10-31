package models.rank;

import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
public class RankInfo {

    private long id;
    private int rank;
    private String name;

    private int head;
    private int body;
    private int leg;

    private long power;       // sức mạnh
    private int taskId;       // id nhiệm vụ
    private int taskIndex;    // tiến độ nhiệm vụ
    private String info;      // mô tả hiển thị (có thể là sức mạnh hoặc nhiệm vụ)

    public void dispose() {
        name = null;
        info = null;
    }
}
