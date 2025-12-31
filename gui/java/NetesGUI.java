import java.awt.Button;
import java.awt.FlowLayout;
import java.awt.Frame;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.IOException;

public class NetesGUI {

    public static void main(String[] args) {

        Frame f = new Frame("NETES");

        f.setLayout(new FlowLayout());

        Button b = new Button("Start");
        b.addActionListener(e -> {
            try {
                ProcessBuilder pb = new ProcessBuilder(
                    "/Users/hirotoshiuchida/NETES/target/debug/netes-engine"
                );

                pb.directory(new java.io.File("/Users/hirotoshiuchida/NETES"));
                pb.inheritIO();
                pb.start();

            } catch (IOException ex) {
                ex.printStackTrace();
            }
        });

        f.add(b);

        f.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                System.exit(0);
            }
        });

        f.setSize(300, 100);
        f.setVisible(true);
    }
}
