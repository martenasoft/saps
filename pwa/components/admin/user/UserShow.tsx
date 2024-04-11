import PageShow from "../page/PageShow";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

const UserShow = (props:{props:any}) => (
  <ShowGuesser {...props}>
    <FieldGuesser source={"email"} />
    <FieldGuesser source={"roles"} />

    <FieldGuesser source={"userIdentifier"} />
    <FieldGuesser source={"defaultStatus"} />
    <FieldGuesser source={"status"} />
  </ShowGuesser>
);

export default UserShow;
