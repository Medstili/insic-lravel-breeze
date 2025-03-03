
import java.util.ArrayList;
import java.util.Arrays;
public class Kata {

  public static int[] arrayDiff(int[] a, int[] b) {
      // Your code here
      ArrayList<Integer> array = new ArrayList<>();
      if(a.length == 0){
        return b;
      }
      if(b.length == 0){
        return a;
      }
      for ( int num : a ){
        for ( int numb : b){
          if (num != numb){
              array.add(num);
          }
        }
      }
      return array.stream().mapToInt(i -> i).toArray();
    }

  public static void main(String[] args) {
      int[] a = {1, 2, 3};
      int[] b = {2};

      int[] result = Kata.arrayDiff(a, b);
      System.out.println(Arrays.toString(result));
  }

}